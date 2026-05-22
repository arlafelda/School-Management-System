<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Attendance;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

        // ================= SUPER ADMIN =================
        if ($role === 'super_admin') {

            $chartData = [
                User::where('role', 'student')->count(),
                User::where('role', 'teacher')->count(),
                User::where('role', 'admin')->count(),
            ];

            return view('dashboard.super-admin-dashboard', [
                'totalStudents' => User::where('role', 'student')->count(),
                'totalTeachers' => Teacher::count(),
                'totalAdmins'   => User::where('role', 'admin')->count(),
                'totalClasses'  => ClassModel::count(),
                'totalMajors'   => ClassModel::distinct('major')->count('major'),
                'totalSubjects' => Subject::count(),

                'activities' => Schedule::with(['classModel', 'teacher'])
                    ->latest()
                    ->take(5)
                    ->get(),

                'chartData' => $chartData,
            ]);
        }

        // ================= ADMIN =================
        if ($role === 'admin') {

            return view('dashboard.admin-dashboard', [
                'totalStudents'  => User::where('role', 'student')->count(),
                'totalTeachers'  => Teacher::count(),
                'totalClasses'   => ClassModel::count(),
                'totalMajors'    => ClassModel::distinct('major')->count('major'),
                'totalSubjects'  => Subject::count(),
                'totalSchedules' => Schedule::count(),

                'activities' => Schedule::with(['classModel', 'teacher'])
                    ->latest()
                    ->take(5)
                    ->get(),
            ]);
        }

        // ================= TEACHER =================
        if ($role === 'teacher') {

            // ambil data teacher berdasarkan user login
            $teacher = Teacher::where('user_id', $user->id)->first();

            if (!$teacher) {
                abort(404, 'Data teacher tidak ditemukan');
            }

            // konversi hari ke Indonesia
            $days = [
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
                'Sunday'    => 'Minggu',
            ];

            $today = $days[now()->format('l')];

            // jadwal guru hari ini
            $todaySchedules = Schedule::with(['classModel', 'teacher'])
                ->where('teacher_id', $teacher->id)
                ->where('day', $today)
                ->get();

            // total jadwal guru
            $totalTeaching = Schedule::where('teacher_id', $teacher->id)
                ->count();

            // total kelas yang diajar
            $totalClasses = Schedule::where('teacher_id', $teacher->id)
                ->distinct('class_id')
                ->count('class_id');

            // total mata pelajaran
            $totalSubjects = Subject::count();

            // total siswa dari kelas yang diajar
            $classIds = Schedule::where('teacher_id', $teacher->id)
                ->pluck('class_id');

            $totalStudents = Student::whereIn('class_id', $classIds)
                ->count();

            // total jadwal hari ini
            $todayCount = $todaySchedules->count();

            // sementara statis
            $attendancePercent = 100;

            return view('dashboard.teacher-dashboard', [
                'todaySchedules'    => $todaySchedules,
                'totalTeaching'     => $totalTeaching,
                'totalStudents'     => $totalStudents,
                'totalClasses'      => $totalClasses,
                'totalSubjects'     => $totalSubjects,
                'todayCount'        => $todayCount,
                'attendancePercent' => $attendancePercent,
            ]);
        }

        // ================= STUDENT =================
        if ($role === 'student') {

            $student = $user->student;

            // ambil absensi milik student login
            $attendances = Attendance::where('student_id', $student->id)
                ->get();

            $totalAttendance = $attendances->count();

            // hitung hadir
            $presentCount = $attendances
                ->where('status', 'hadir')
                ->count();

            $attendancePercent = $totalAttendance > 0
                ? round(($presentCount / $totalAttendance) * 100, 1)
                : 0;

            // ubah hari ke Indonesia
            $hariMap = [
                'Monday'    => 'Senin',
                'Tuesday'   => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday'  => 'Kamis',
                'Friday'    => 'Jumat',
                'Saturday'  => 'Sabtu',
                'Sunday'    => 'Minggu'
            ];

            $today = $hariMap[now()->format('l')];

            // jadwal kelas siswa login hari ini
            $todaySchedules = Schedule::with(['teacher', 'class'])
                ->where('class_id', $student->class_id)
                ->where('day', $today)
                ->get();

            return view('dashboard.student-dashboard', [
                'attendancePercent' => $attendancePercent,
                'totalAttendance'   => $totalAttendance,
                'todaySchedules'    => $todaySchedules,
            ]);
        }

        abort(403, 'Role tidak dikenali');
    }
}