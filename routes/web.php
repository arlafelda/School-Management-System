<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExtracurricularController;

Route::get('/', function () {
    return view('welcome');
});

// 🔥 redirect default dashboard
Route::get('/dashboard', function () {
    return redirect('/login');
})->middleware(['auth'])->name('dashboard');

// 👑 Super Admin
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard/superadmin', [DashboardController::class, 'index'])
        ->name('dashboard.superadmin');
});

// 🧑‍💼 Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])
        ->name('dashboard.admin');
});

// 👨‍🏫 Teacher
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard/teacher', function () {
        return view('dashboard.teacher-dashboard');
    });
});

// 🎓 Student
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard/student', [DashboardController::class, 'index'])
        ->name('dashboard.student');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/{id}', [AdminController::class, 'show'])->name('admin.show');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('admin.edit');
        Route::put('/{id}', [AdminController::class, 'update'])->name('admin.update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('admin.delete');
    });
});

Route::middleware(['auth', 'role:super_admin,admin'])->prefix('teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('teacher.index');
    Route::get('/create', [TeacherController::class, 'create'])->name('teacher.create');
    Route::post('/', [TeacherController::class, 'store'])->name('teacher.store');
    Route::get('/{id}', [TeacherController::class, 'show'])->name('teacher.show');
    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
    Route::put('/{id}', [TeacherController::class, 'update'])->name('teacher.update');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('teacher.delete');
});

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{id}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{id}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{id}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{id}', [StudentController::class, 'destroy'])->name('students.delete');
});

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->group(function () {
    Route::get('/classes', [ClassesController::class, 'index'])->name('class.index');
    Route::get('/classes/create', [ClassesController::class, 'create'])->name('class.create');
    Route::post('/classes', [ClassesController::class, 'store'])->name('class.store');
    Route::get('/classes/{id}/edit', [ClassesController::class, 'edit'])->name('class.edit');
    Route::put('/classes/{id}', [ClassesController::class, 'update'])->name('class.update');
    Route::delete('/classes/{id}', [ClassesController::class, 'destroy'])->name('class.delete');
    Route::get('/class/{id}', [ClassesController::class, 'show'])->name('class.show');
});

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->prefix('schedule')->group(function () {
    Route::get('/', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::get('/create', [ScheduleController::class, 'create'])->name('schedule.create');
    Route::post('/', [ScheduleController::class, 'store'])->name('schedule.store');
    Route::get('/{id}', [ScheduleController::class, 'show'])->name('schedule.show');
    Route::get('/{id}/edit', [ScheduleController::class, 'edit'])->name('schedule.edit');
    Route::put('/{id}', [ScheduleController::class, 'update'])->name('schedule.update');
    Route::delete('/{id}', [ScheduleController::class, 'destroy'])->name('schedule.delete');
});

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->group(function () {
    Route::get('/grades', [GradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/create', [GradeController::class, 'create'])->name('grades.create');
    Route::post('/grades/store', [GradeController::class, 'store'])->name('grades.store');
    Route::get('/grades/{id}', [GradeController::class, 'show'])->name('grades.show');
    Route::get('/grades/{id}/edit', [GradeController::class, 'edit'])->name('grades.edit');
    Route::put('/grades/{id}', [GradeController::class, 'update'])->name('grades.update');
    Route::delete('/grades/{id}', [GradeController::class, 'destroy'])->name('grades.destroy');
});

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/recap', [AttendanceController::class, 'recap'])->name('attendance.recap');
    Route::get('/attendance/{id}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/extracurricular', [ExtracurricularController::class, 'index'])->name('extracurricular.index');
Route::get('/extracurricular/create', [ExtracurricularController::class, 'create'])->name('extracurricular.create');
Route::post('/extracurricular/store', [ExtracurricularController::class, 'store'])->name('extracurricular.store');
Route::get('/extracurricular/{id}', [ExtracurricularController::class, 'show'])->name('extracurricular.show');
Route::get('/extracurricular/{id}/edit', [ExtracurricularController::class, 'edit'])->name('extracurricular.edit');
Route::put('/extracurricular/{id}', [ExtracurricularController::class, 'update'])->name('extracurricular.update');
Route::delete('/extracurricular/{id}', [ExtracurricularController::class, 'destroy'])->name('extracurricular.destroy');
Route::post('/extracurricular/{id}/join', [ExtracurricularController::class, 'join'])->name('extracurricular.join');


// halaman ekskul siswa
Route::get('/student/extracurricular', [ExtracurricularController::class, 'studentExtracurricular'])
    ->middleware(['auth', 'role:student'])
    ->name('student.extracurricular');

// join ekskul
Route::post('/extracurricular/{id}/join', [ExtracurricularController::class, 'join'])
    ->middleware(['auth', 'role:student'])
    ->name('extracurricular.join');