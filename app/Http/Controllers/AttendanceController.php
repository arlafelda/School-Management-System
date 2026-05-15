<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    // =====================
    // GET TEACHER LOGIN
    // =====================
    private function getTeacher()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'teacher') {
            return null;
        }

        return Teacher::where('user_id', $user->id)->first();
    }

    // =====================
    // INDEX
    // =====================
    public function index(Request $request)
{
    $user = Auth::user();

    $classes = ClassModel::all();

    $query = Attendance::with([
        'student.class',
        'schedule.teacher'
    ]);

    /*
    =====================
    STUDENT
    =====================
    */
    if ($user->role === 'student') {

        $student = Student::with('class')
            ->where('user_id', $user->id)
            ->first();

        if ($student) {

            // hanya data student sendiri
            $query->where('student_id', $student->id);

            // paksa class_id sesuai kelas student
            $request->merge([
                'class_id' => $student->class_id
            ]);

        } else {
            $query->whereRaw('1 = 0');
        }
    }

    /*
    =====================
    TEACHER
    =====================
    */
    elseif ($user->role === 'teacher') {

        $teacher = $this->getTeacher();

        if ($teacher) {
            $scheduleIds = Schedule::where(
                'teacher_id',
                $teacher->id
            )->pluck('id');

            $query->whereIn(
                'schedule_id',
                $scheduleIds
            );
        } else {
            $query->whereRaw('1 = 0');
        }
    }

    /*
    =====================
    FILTER CLASS
    =====================
    */
    if ($request->class_id) {
        $query->whereHas('student', function ($q) use ($request) {
            $q->where(
                'class_id',
                $request->class_id
            );
        });
    }

    /*
    =====================
    FILTER STATUS
    =====================
    */
    if ($request->status) {
        $query->where(
            'status',
            $request->status
        );
    }

    /*
    =====================
    FILTER DATE
    =====================
    */
    if ($request->date) {
        $query->whereDate(
            'date',
            $request->date
        );
    }

    $attendances = $query->latest()->get();

    $total = $attendances->count();

    $hadir = $attendances
        ->where('status', 'hadir')
        ->count();

    $persen = $total
        ? round(($hadir / $total) * 100, 1)
        : 0;

    return view(
        'attendance.attendance-index',
        compact(
            'attendances',
            'classes',
            'persen'
        )
    );
}

    // =====================
    // CREATE
    // =====================
    public function create(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'student') {
            abort(403, 'Student tidak memiliki akses');
        }

        $classes = ClassModel::all();

        $date = $request->date ?? date('Y-m-d');

        /*
    =========================
    KONVERSI HARI INDONESIA
    =========================
    */
        $hariMap = [
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu',
            'Sunday'    => 'Minggu',
        ];

        $englishDay = Carbon::parse($date)->format('l');
        $today = $hariMap[$englishDay];

        $schedules = collect();
        $students = collect();

        /*
    =========================
    TEACHER
    =========================
    */
        if ($user->role === 'teacher') {

            $teacher = $this->getTeacher();

            if (!$teacher) {
                return redirect()
                    ->route('attendance.index')
                    ->with('error', 'Teacher tidak ditemukan');
            }

            $schedules = Schedule::with('class')
                ->where('teacher_id', $teacher->id)
                ->where('day', $today)
                ->get();
        }

        /*
    =========================
    ADMIN / SUPER ADMIN
    =========================
    */ else {

            $schedules = Schedule::with(['teacher', 'class'])
                ->where('day', $today)
                ->get();
        }

        /*
    =========================
    FILTER STUDENT BERDASARKAN
    schedule_id YANG DIPILIH
    =========================
    */
        if ($request->schedule_id) {

            $selectedSchedule = Schedule::where('id', $request->schedule_id)
                ->where('day', $today)
                ->first();

            if ($selectedSchedule) {
                $students = Student::with('class')
                    ->where('class_id', $selectedSchedule->class_id)
                    ->get();
            }
        }

        /*
    =========================
    CEK ABSENSI SUDAH ADA?
    =========================
    */
        $attendanceExists = false;

        if ($request->schedule_id) {
            $attendanceExists = Attendance::where(
                'schedule_id',
                $request->schedule_id
            )
                ->whereDate('date', $date)
                ->exists();
        }

        $showForm =
            $request->schedule_id &&
            !$attendanceExists &&
            $students->count() > 0;

        return view('attendance.attendance-add', compact(
            'classes',
            'schedules',
            'students',
            'date',
            'attendanceExists',
            'showForm'
        ));
    }

    // =====================
    // STORE
    // =====================
    public function store(Request $request)
    {
        $user = Auth::user();

        // =====================
        // BLOCK STUDENT ACCESS
        // =====================
        if ($user->role === 'student') {

            return response()->json([
                'status' => false,
                'message' => 'Student tidak memiliki akses'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:tbl_schedules,id',
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

        // =====================
        // TEACHER SECURITY
        // =====================
        if ($user->role === 'teacher') {

            $teacher = $this->getTeacher();

            $scheduleValid = Schedule::where('id', $request->schedule_id)
                ->where('teacher_id', $teacher->id)
                ->exists();

            if (!$scheduleValid) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak memiliki akses'
                ], 403);
            }
        }

        $attendanceExists = Attendance::where('schedule_id', $request->schedule_id)
            ->whereDate('date', $request->date)
            ->exists();

        if ($attendanceExists) {
            return response()->json([
                'status' => false,
                'message' => 'Absensi untuk jadwal dan tanggal ini sudah ada. Silakan edit data absensi yang sudah dibuat.'
            ], 409);
        }

        foreach ($request->student_id as $index => $studentId) {
            $status = $request->attendance[$studentId] ?? null;
            if (!$status) continue;

            Attendance::updateOrCreate(
                ['student_id' => $studentId, 'schedule_id' => $request->schedule_id, 'date' => $request->date],
                ['status' => $status]
            );
        }

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil disimpan'
        ]);
    }

    // =====================
    // EDIT
    // =====================
    public function edit(int $id)
{
    $user = Auth::user();

    /*
    =====================
    BLOCK STUDENT ACCESS
    =====================
    */
    if ($user->role === 'student') {
        abort(403, 'Student tidak memiliki akses');
    }

    $attendance = Attendance::with([
        'student.class',
        'schedule.teacher',
        'schedule.class'
    ])->findOrFail($id);

    /*
    =====================
    TEACHER SECURITY
    =====================
    */
    if ($user->role === 'teacher') {

        $teacher = $this->getTeacher();

        if (
            !$teacher ||
            $attendance->schedule->teacher_id != $teacher->id
        ) {
            abort(403, 'Anda tidak memiliki akses');
        }
    }

    /*
    =====================
    LOAD FILTER DATA
    =====================
    */
    $classes = ClassModel::all();

    if ($user->role === 'teacher') {

        $teacher = $this->getTeacher();

        $schedules = Schedule::with([
            'teacher',
            'class'
        ])
        ->where('teacher_id', $teacher->id)
        ->get();

        $attendances = Attendance::with([
            'student.class',
            'schedule.teacher'
        ])
        ->whereHas('schedule', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })
        ->get();

    } else {

        $schedules = Schedule::with([
            'teacher',
            'class'
        ])->get();

        $attendances = Attendance::with([
            'student.class',
            'schedule.teacher'
        ])->get();
    }

    /*
    =====================
    HITUNG PERSENTASE
    =====================
    */
    $total = $attendances->count();

    $hadir = $attendances
        ->where('status', 'hadir')
        ->count();

    $persen = $total
        ? round(($hadir / $total) * 100, 1)
        : 0;

    return view(
        'attendance.attendance-edit',
        compact(
            'attendance',
            'classes',
            'schedules',
            'attendances',
            'persen'
        )
    );
}

    // =====================
    // UPDATE
    // =====================
    public function update(Request $request, int $id)
    {
        $user = Auth::user();

        // =====================
        // BLOCK STUDENT ACCESS
        // =====================
        if ($user->role === 'student') {

            return response()->json([
                'status' => false,
                'message' => 'Student tidak memiliki akses'
            ], 403);
        }

        $attendance = Attendance::with('schedule')
            ->findOrFail($id);

        // =====================
        // TEACHER SECURITY
        // =====================
        if ($user->role === 'teacher') {

            $teacher = $this->getTeacher();

            if (
                !$teacher ||
                $attendance->schedule->teacher_id != $teacher->id
            ) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak memiliki akses'
                ], 403);
            }
        }

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

    // =====================
    // DELETE
    // =====================
    public function destroy(int $id)
    {
        $user = Auth::user();

        // =====================
        // BLOCK STUDENT ACCESS
        // =====================
        if ($user->role === 'student') {

            return response()->json([
                'status' => false,
                'message' => 'Student tidak memiliki akses'
            ], 403);
        }

        $attendance = Attendance::with('schedule')
            ->findOrFail($id);

        // =====================
        // TEACHER SECURITY
        // =====================
        if ($user->role === 'teacher') {

            $teacher = $this->getTeacher();

            if (
                !$teacher ||
                $attendance->schedule->teacher_id != $teacher->id
            ) {

                return response()->json([
                    'status' => false,
                    'message' => 'Tidak memiliki akses'
                ], 403);
            }
        }

        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil dihapus'
        ]);
    }

    // =====================
    // RECAP
    // =====================
    public function recap(Request $request)
    {
        $user = Auth::user();

        $classes = ClassModel::all();

        $query = Attendance::with('student.class');

        // =====================
        // STUDENT ONLY OWN RECAP
        // =====================
        if ($user->role === 'student') {

            $student = Student::where('user_id', $user->id)->first();

            if ($student) {

                $query->where('student_id', $student->id);
            } else {

                $query->whereRaw('1 = 0');
            }
        }

        // =====================
        // TEACHER ONLY OWN SCHEDULE
        // =====================
        elseif ($user->role === 'teacher') {

            $teacher = $this->getTeacher();

            if ($teacher) {

                $scheduleIds = Schedule::where('teacher_id', $teacher->id)
                    ->pluck('id');

                $query->whereIn('schedule_id', $scheduleIds);
            }
        }

        // =====================
        // FILTER CLASS
        // =====================
        if (
            in_array($user->role, ['super_admin', 'admin', 'teacher'])
            && $request->class_id
        ) {

            $query->whereHas('student', function ($s) use ($request) {

                $s->where('class_id', $request->class_id);
            });
        }

        $rekap = $query
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
