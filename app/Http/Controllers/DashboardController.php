<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Schedule;
use App\Models\Attendance;

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
                'totalSubjects' => Teacher::distinct('subject')->count('subject'),

                'activities' => Schedule::with(['class', 'teacher'])
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
                'totalSchedules' => Schedule::count(),

            ]);
        }

        // ================= TEACHER =================
if ($role === 'teacher') {

    $today = now()->format('l');

    $todaySchedules = Schedule::with('class')
        ->where('teacher_id', $user->id)
        ->where('day', $today)
        ->get();

    // total jam mengajar
    $totalTeaching = $todaySchedules->count();

    // total siswa
    $totalStudents = User::where('role', 'student')->count();

    // sementara statis dulu
    $attendancePercent = 100;

    return view('dashboard.teacher-dashboard', [

        'todaySchedules'    => $todaySchedules,
        'totalTeaching'     => $totalTeaching,
        'totalStudents'     => $totalStudents,
        'attendancePercent' => $attendancePercent,

    ]);
}

        // ================= STUDENT =================
        if ($role === 'student') {

            $attendances = Attendance::where('student_id', $user->id)->get();

            $totalAttendance = $attendances->count();

            $presentCount = $attendances
                ->where('status', 'present')
                ->count();

            $attendancePercent = $totalAttendance > 0
                ? round(($presentCount / $totalAttendance) * 100)
                : 0;

            $today = now()->format('l');

            $todaySchedules = Schedule::with(['teacher', 'class'])
                ->where('day', $today)
                ->get();

            return view('dashboard.student-dashboard', [

                'attendancePercent' => $attendancePercent,
                'totalAttendance'   => $totalAttendance,
                'todaySchedules'    => $todaySchedules,

            ]);
        }

        abort(403);
    }
}