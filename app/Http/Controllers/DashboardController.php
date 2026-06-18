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

        // Map hari Inggris -> Indonesia (dipakai semua role)
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

            $totalStudents = User::where('role', 'student')->where('archived', 0)->count();
            $totalTeachers = User::where('role', 'teacher')->where('archived', 0)->count();
            $totalAdmins   = User::where('role', 'admin')->where('archived', 0)->count();

            $chartData = [
                $totalStudents,
                $totalTeachers,
                $totalAdmins,
            ];

            return view('dashboard.super-admin-dashboard', [
                'totalStudents' => $totalStudents,
                'totalTeachers' => $totalTeachers,
                'totalAdmins'   => $totalAdmins,
                'totalClasses'  => ClassModel::where('archived', 0)->count(),
                'totalMajors'   => ClassModel::where('archived', 0)->distinct('major')->count('major'),
                'totalSubjects' => Subject::where('archived', 0)->count(),
                'today'         => $today,

                // Aktivitas hari ini, diurutkan dari jam paling pagi
                'activities' => Schedule::with(['class', 'teacher'])
                    ->where('archived', 0)
                    ->where('day', $today)
                    ->orderBy('start_time', 'asc')
                    ->get(),

                'chartData' => $chartData,
            ]);
        }

        // ================= ADMIN =================
        if ($role === 'admin') {

            $totalStudents = User::where('role', 'student')->where('archived', 0)->count();
            $totalTeachers = User::where('role', 'teacher')->where('archived', 0)->count();

            $chartData = [
                $totalStudents,
                $totalTeachers,
                User::where('role', 'admin')->where('archived', 0)->count(),
            ];

            return view('dashboard.admin-dashboard', [
                'totalStudents'  => $totalStudents,
                'totalTeachers'  => $totalTeachers,
                'totalClasses'   => ClassModel::where('archived', 0)->count(),
                'totalMajors'    => ClassModel::where('archived', 0)->distinct('major')->count('major'),
                'totalSubjects'  => Subject::where('archived', 0)->count(),
                'totalSchedules' => Schedule::where('archived', 0)->count(),
                'today'          => $today,

                // Aktivitas hari ini, diurutkan dari jam paling pagi
                'activities' => Schedule::with(['class', 'teacher'])
                    ->where('archived', 0)
                    ->where('day', $today)
                    ->orderBy('start_time', 'asc')
                    ->get(),

                'chartData' => $chartData,
            ]);
        }

        // ================= TEACHER =================
        if ($role === 'teacher') {
            // Load sekaligus relasi subjects (pivot teacher_subject)
            $teacher = Teacher::with('subjects')
                ->where('user_id', $user->id)
                ->first();
 
            if (!$teacher) {
                abort(404, 'Data teacher tidak ditemukan');
            }
 
            // Jadwal hari ini — tidak perlu eager load teacher (sudah tahu gurunya)
            $todaySchedules = Schedule::with(['class', 'subject'])
                ->where('teacher_id', $teacher->id)
                ->where('day', $today)
                ->where('archived', 0)
                ->orderBy('start_time', 'asc')
                ->get();
 
            // Kelas wali — tbl_classes.teacher_id = id wali kelas
            $homeroomClass = ClassModel::where('teacher_id', $teacher->id)
                ->where('archived', 0)
                ->first();
 
            // Jumlah siswa HANYA dari kelas wali
            $totalStudents = $homeroomClass
                ? Student::where('class_id', $homeroomClass->id)
                    ->where('archived', 0)
                    ->count()
                : 0;
 
            // Mapel dari pivot teacher_subject (bukan dari schedules)
            $subjects      = $teacher->subjects()->where('archived', 0)->get();
            $totalSubjects = $subjects->count();
 
            // Jumlah kelas yang diajar (distinct dari schedules)
            $totalClasses  = Schedule::where('teacher_id', $teacher->id)
                ->where('archived', 0)
                ->distinct('class_id')
                ->count('class_id');
 
            return view('dashboard.teacher-dashboard', [
                'todaySchedules' => $todaySchedules,
                'totalStudents'  => $totalStudents,   // siswa wali kelas
                'homeroomClass'  => $homeroomClass,   // data kelas wali (bisa null)
                'totalClasses'   => $totalClasses,    // kelas yang diajar
                'subjects'       => $subjects,        // koleksi Subject
                'totalSubjects'  => $totalSubjects,   // jumlah mapel
                'todayCount'     => $todaySchedules->count(),
                'today'          => $today,
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
 
            // ⬇ Tambah 'subject' di eager load agar nama mapel tampil di view
            $todaySchedules = Schedule::with(['teacher', 'class', 'subject'])
                ->where('class_id', $student->class_id)
                ->where('day', $today)
                ->where('archived', 0)
                ->orderBy('start_time', 'asc')
                ->get();
 
            return view('dashboard.student-dashboard', [
                'attendancePercent' => $attendancePercent,
                'totalAttendance'   => $totalAttendance,
                'todaySchedules'    => $todaySchedules,
                'today'             => $today,
            ]);
        }
 
        abort(403, 'Role tidak dikenali');
    }
}