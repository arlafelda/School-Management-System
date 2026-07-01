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
use App\Models\Announcement; // ← tambahkan ini

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->role;

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

        // ================= SUPER ADMIN =================
        if ($role === 'super_admin') {
            $totalStudents = User::where('role', 'student')->count();
            $totalTeachers = User::where('role', 'teacher')->count();
            $totalAdmins   = User::where('role', 'admin')->count();

            return view('dashboard.super-admin-dashboard', [
                'totalStudents' => $totalStudents,
                'totalTeachers' => $totalTeachers,
                'totalAdmins'   => $totalAdmins,
                'totalClasses'  => ClassModel::count(),
                'totalMajors'   => ClassModel::distinct('major')->count('major'),
                'totalSubjects' => Subject::count(),
                'today'         => $today,
                'activities'    => Schedule::with(['class', 'teacher'])
                    ->where('day', $today)
                    ->orderBy('start_time', 'asc')
                    ->get(),
                'chartData' => [$totalStudents, $totalTeachers, $totalAdmins],
            ]);
        }

        // ================= ADMIN =================
        if ($role === 'admin') {
            $totalStudents = User::where('role', 'student')->count();
            $totalTeachers = User::where('role', 'teacher')->count();

            return view('dashboard.admin-dashboard', [
                'totalStudents'  => $totalStudents,
                'totalTeachers'  => $totalTeachers,
                'totalClasses'   => ClassModel::count(),
                'totalMajors'    => ClassModel::distinct('major')->count('major'),
                'totalSubjects'  => Subject::count(),
                'totalSchedules' => Schedule::count(),
                'today'          => $today,
                'activities'     => Schedule::with(['class', 'teacher'])
                    ->where('day', $today)
                    ->orderBy('start_time', 'asc')
                    ->get(),
                'chartData' => [
                    $totalStudents,
                    $totalTeachers,
                    User::where('role', 'admin')->count(),
                ],
            ]);
        }

        // ================= TEACHER =================
        if ($role === 'teacher') {
            $teacher = Teacher::with('subjects')
                ->where('user_id', $user->id)
                ->first();

            if (!$teacher) {
                abort(404, 'Data teacher tidak ditemukan');
            }

            $todaySchedules = Schedule::with(['class', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->where('day', $today)
                ->orderBy('start_time', 'asc')
                ->get();

            $homeroomClass = ClassModel::where('teacher_id', $teacher->id)->first();

            $totalStudents = $homeroomClass
                ? Student::where('class_id', $homeroomClass->id)->count()
                : 0;

            $subjects      = $teacher->subjects;
            $totalSubjects = $subjects->count();
            $totalClasses  = Schedule::where('teacher_id', $teacher->id)
                ->distinct('class_id')
                ->count('class_id');

            // ← Tambahan: ambil pengumuman untuk teacher
            $announcements = Announcement::active()
                ->forRole('teacher')
                ->with('author')
                ->orderByRaw("FIELD(priority, 'mendesak', 'penting', 'normal')")
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard.teacher-dashboard', [
                'todaySchedules' => $todaySchedules,
                'totalStudents'  => $totalStudents,
                'homeroomClass'  => $homeroomClass,
                'totalClasses'   => $totalClasses,
                'subjects'       => $subjects,
                'totalSubjects'  => $totalSubjects,
                'todayCount'     => $todaySchedules->count(),
                'today'          => $today,
                'announcements'  => $announcements, // ← tambahkan ini
            ]);
        }

        // ================= STUDENT =================
        if ($role === 'student') {
            $student = $user->student;

            if (!$student) {
                abort(404, 'Data student tidak ditemukan');
            }

            $attendances       = Attendance::where('student_id', $student->id)->get();
            $totalAttendance   = $attendances->count();
            $presentCount      = $attendances->where('status', 'hadir')->count();
            $attendancePercent = $totalAttendance > 0
                ? round(($presentCount / $totalAttendance) * 100, 1)
                : 0;

            $todaySchedules = Schedule::with(['teacher', 'class', 'subject'])
                ->where('class_id', $student->class_id)
                ->where('day', $today)
                ->orderBy('start_time', 'asc')
                ->get();

            // ← Tambahan: ambil pengumuman untuk student
            $announcements = Announcement::active()
                ->forRole('student')
                ->with('author')
                ->orderByRaw("FIELD(priority, 'mendesak', 'penting', 'normal')")
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard.student-dashboard', [
                'attendancePercent' => $attendancePercent,
                'totalAttendance'   => $totalAttendance,
                'todaySchedules'    => $todaySchedules,
                'today'             => $today,
                'announcements'     => $announcements, // ← tambahkan ini
            ]);
        }

        abort(403, 'Role tidak dikenali');
    }
}