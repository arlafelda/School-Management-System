<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Subject;
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
        $subjects = Subject::all();

        $query = Attendance::with([
            'student.class',
            'schedule.teacher',
            'schedule.subject',
            'schedule.classModel'
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

                $query->where('student_id', $student->id);

                $request->merge([
                    'class_id' => $student->class_id
                ]);

            } else {

                $query->whereRaw('1=0');

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

                $query->whereRaw('1=0');

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
        FILTER SUBJECT
        =====================
        */
        if ($request->subject_id) {

            $query->whereHas('schedule', function ($q) use ($request) {
                $q->where(
                    'subject_id',
                    $request->subject_id
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

        return view('attendance.attendance-index', compact(
            'attendances',
            'classes',
            'subjects',
            'persen'
        ));
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
        $subjects = Subject::all();

        $date = $request->date ?? date('Y-m-d');

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
        =====================
        TEACHER
        =====================
        */
        if ($user->role === 'teacher') {

            $teacher = $this->getTeacher();

            $schedules = Schedule::with([
                'classModel:id,name',
                'subject:id,name'
            ])
            ->where('teacher_id', $teacher->id)
            ->where('day', $today)
            ->get();
        }

        /*
        =====================
        ADMIN
        =====================
        */
        else {

            $schedules = Schedule::with([
                'teacher:id,name',
                'classModel:id,name',
                'subject:id,name'
            ])
            ->where('day', $today)
            ->get();
        }

        /*
        =====================
        GET STUDENTS
        =====================
        */
        if ($request->schedule_id) {

            $selectedSchedule = Schedule::with('subject')
                ->where('id', $request->schedule_id)
                ->where('day', $today)
                ->first();

            if ($selectedSchedule) {

                $students = Student::with('class')
                    ->where(
                        'class_id',
                        $selectedSchedule->class_id
                    )
                    ->get();
            }
        }

        /*
        =====================
        CHECK ATTENDANCE
        =====================
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

        $showForm = $request->schedule_id
            && !$attendanceExists
            && $students->count() > 0;

        return view('attendance.attendance-add', compact(
            'classes',
            'subjects',
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

        if ($user->role === 'student') {
            return response()->json([
                'status' => false,
                'message' => 'Student tidak memiliki akses'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required|exists:tbl_schedules,id',
            'date'        => 'required|date',
            'student_id'  => 'required|array'
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

            if (!$status) {
                continue;
            }

            Attendance::updateOrCreate(
                [
                    'student_id'  => $studentId,
                    'schedule_id' => $request->schedule_id,
                    'date'        => $request->date
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


    // =====================
    // EDIT
    // =====================
    public function edit(int $id)
    {
        $attendance = Attendance::with([
            'student.class',
            'schedule.teacher',
            'schedule.subject',
            'schedule.classModel'
        ])->findOrFail($id);

        $classes = ClassModel::all();
        $subjects = Subject::all();

        $schedules = Schedule::with([
            'teacher',
            'classModel',
            'subject'
        ])->get();

        return view('attendance.attendance-edit', compact(
            'attendance',
            'classes',
            'subjects',
            'schedules'
        ));
    }


    // =====================
    // UPDATE
    // =====================
    public function update(Request $request, int $id)
    {
        $attendance = Attendance::findOrFail($id);

        $attendance->update([
            'schedule_id' => $request->schedule_id,
            'date'        => $request->date,
            'status'      => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil diperbarui'
        ]);
    }


    // =====================
    // RECAP
    // =====================
    public function recap(Request $request)
{
    $user = Auth::user();

    $classes = ClassModel::all();
    $subjects = Subject::all();

    $query = Attendance::with([
        'student.class',
        'schedule.subject'
    ]);

    /*
    =====================
    STUDENT
    =====================
    */
    if ($user->role === 'student') {

        $student = Student::where('user_id', $user->id)->first();

        if ($student) {
            $query->where('student_id', $student->id);
        } else {
            $query->whereRaw('1=0');
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

            $scheduleIds = Schedule::where('teacher_id', $teacher->id)
                ->pluck('id');

            $query->whereIn('schedule_id', $scheduleIds);

        } else {
            $query->whereRaw('1=0');
        }
    }

    /*
    =====================
    FILTER MAJOR
    =====================
    */
    if ($request->major) {
        $query->whereHas('student.class', function ($q) use ($request) {
            $q->where('major', $request->major);
        });
    }

    /*
    =====================
    FILTER CLASS
    =====================
    */
    if ($request->class_id) {
        $query->whereHas('student', function ($q) use ($request) {
            $q->where('class_id', $request->class_id);
        });
    }

    /*
    =====================
    FILTER SUBJECT (BARU)
    =====================
    */
    if ($request->subject_id) {
        $query->whereHas('schedule', function ($q) use ($request) {
            $q->where('subject_id', $request->subject_id);
        });
    }

    /*
    =====================
    REKAP DATA
    =====================
    */
    $rekap = $query->get()
        ->groupBy('student_id')
        ->map(function ($items) {

            $first = $items->first();

            return (object)[
                'student' => $first->student,
                'hadir'   => $items->where('status', 'hadir')->count(),
                'izin'    => $items->where('status', 'izin')->count(),
                'alpa'    => $items->where('status', 'alpa')->count(),
            ];
        });

    return view('attendance.attendance-recap', compact(
        'rekap',
        'classes',
        'subjects'
    ));
}


    // =====================
    // DELETE
    // =====================
    public function destroy(int $id)
    {
        Attendance::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Absensi berhasil dihapus'
        ]);
    }
}