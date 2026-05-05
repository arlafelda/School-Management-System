<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    // ===================== INDEX =====================
    public function index(Request $request)
    {
        $classes = ClassModel::all();

        $attendances = Attendance::with(['student.class', 'schedule.teacher'])
            ->when($request->class_id, function ($q) use ($request) {
                $q->whereHas('student', fn($s) =>
                    $s->where('class_id', $request->class_id)
                );
            })
            ->when($request->date, function ($q) use ($request) {
                $q->whereDate('date', $request->date);
            })
            ->get();

        $total = $attendances->count();
        $hadir = $attendances->where('status', 'hadir')->count();
        $persen = $total ? round(($hadir / $total) * 100, 1) : 0;

        return view('attendance.attendance-index', compact(
            'attendances',
            'classes',
            'persen'
        ));
    }

    // ===================== CREATE =====================
    // ===================== CREATE =====================
    public function create(Request $request)
    {
        $classes = ClassModel::all();
        $date = $request->date ?? date('Y-m-d');

        $day = Carbon::parse($date)->locale('id')->dayName;

        // 🔥 Ambil jadwal
        $schedules = Schedule::with('teacher')
            ->when($request->class_id, fn($q) =>
                $q->where('class_id', $request->class_id)
            )
            ->where('day', ucfirst($day))
            ->get();

        $students = collect();

        // 🔥 PERBAIKAN: jangan terlalu ketat
        if ($request->class_id && $request->schedule_id) {

            $students = Student::where('class_id', $request->class_id)
                ->whereDoesntHave('attendances', function ($q) use ($request, $date) {
                    $q->where('schedule_id', $request->schedule_id)
                      ->whereDate('date', $date);
                })
                ->get();
        }

        return view('attendance.attendance-add', compact(
            'students',
            'classes',
            'schedules',
            'date'
        ));
    }

    // ===================== STORE (AJAX FIX) =====================
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required',
            'date' => 'required|date',
            'student_id' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->student_id as $studentId) {

            $status = $request->attendance[$studentId] ?? null;
            if (!$status) continue;

            Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'schedule_id' => $request->schedule_id,
                    'date' => $request->date,
                ],
                [
                    'status' => $status
                ]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil disimpan'
        ]);
    }

    // ===================== UPDATE (AJAX FIX) =====================
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required',
            'date' => 'required|date',
            'status' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'schedule_id' => $request->schedule_id,
            'date' => $request->date,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil diperbarui'
        ]);
    }

    // ===================== DELETE (AJAX FIX) =====================
    public function destroy(Request $request, int $id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil dihapus'
        ]);
    }

    // ===================== RECAP =====================
    public function recap(Request $request)
    {
        $classes = ClassModel::all();

        $rekap = Attendance::with('student.class')
            ->when($request->class_id, fn($q) =>
                $q->whereHas('student', fn($s) =>
                    $s->where('class_id', $request->class_id)
                )
            )
            ->selectRaw('
                student_id,
                SUM(CASE WHEN status="hadir" THEN 1 ELSE 0 END) as hadir,
                SUM(CASE WHEN status="izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status="sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status="alpa" THEN 1 ELSE 0 END) as alpa
            ')
            ->groupBy('student_id')
            ->get();

        return view('attendance.attendance-recap', compact(
            'rekap',
            'classes'
        ));
    }
}