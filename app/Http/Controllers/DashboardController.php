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

            // 🔥 Chart distribusi role
            $chartData = [
                User::where('role', 'student')->count(),
                User::where('role', 'teacher')->count(),
                User::where('role', 'admin')->count(),
            ];

            return view('dashboard.super-admin-dashboard', [
                // STAT CARD
                'totalStudents' => User::where('role', 'student')->count(),
                'totalTeachers' => Teacher::count(),
                'totalAdmins'   => User::where('role', 'admin')->count(),
                'totalClasses'  => ClassModel::count(),
                'totalMajors'   => ClassModel::distinct('major')->count('major'),
                'totalSubjects' => Teacher::distinct('subject')->count('subject'),

                // AKTIVITAS
                'activities' => Schedule::with(['class', 'teacher'])
                    ->latest()
                    ->take(5)
                    ->get(),

                // CHART
                'chartData' => $chartData,
            ]);
        }

        // ================= ADMIN =================
        if ($role === 'admin') {

            return view('dashboard.admin-dashboard', [
                'totalStudents'  => User::where('role', 'student')->count(),
                'totalTeachers'  => Teacher::count(),
                'totalClasses'   => ClassModel::count(),
                'totalSchedules' => Schedule::count(), // 🔥 FIX
            ]);
        }

        // ================= TEACHER =================
        if ($role === 'teacher') {
            return view('dashboard.teacher', [
                'schedules' => Schedule::where('teacher_id', $user->id)->get()
            ]);
        }

        // ================= STUDENT =================
        if ($user->role === 'student') {

            $attendances = Attendance::where('student_id', $user->id)->get();

            $totalAttendance = $attendances->count();
            $presentCount = $attendances->where('status', 'present')->count();

            $attendancePercent = $totalAttendance > 0
                ? round(($presentCount / $totalAttendance) * 100)
                : 0;

            // 🔥 ambil hari sekarang (format: Monday, Tuesday, dll)
            $today = now()->format('l');

            $todaySchedules = Schedule::with(['teacher', 'class'])
                ->where('day', $today)
                ->get();

            return view('dashboard.student-dashboard', [
                'attendancePercent' => $attendancePercent,
                'totalAttendance' => $totalAttendance,
                'todaySchedules' => $todaySchedules,
            ]);
        }
        abort(403);
    }
}