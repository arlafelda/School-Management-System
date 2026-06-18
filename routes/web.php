<?php

use illuminate\Support\Facades\Auth;
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
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('welcome');
});

// 🔥 redirect default dashboard
Route::get('/dashboard', function () {

    $user = auth::user();

    return match ($user->role) {
        'super_admin' => redirect()->route('dashboard.super_admin'),
        'admin'       => redirect()->route('dashboard.admin'),
        'teacher'     => redirect()->route('dashboard.teacher'),
        'student'     => redirect()->route('dashboard.student'),
        default       => redirect('/'),
    };

})->middleware('auth')->name('dashboard');

// 👑 Super Admin
Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/dashboard/superadmin', [DashboardController::class, 'index'])
        ->name('dashboard.super_admin');
});

// 🧑‍💼 Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [DashboardController::class, 'index'])
        ->name('dashboard.admin');
});

// 👨‍🏫 Teacher
Route::middleware(['auth', 'role:teacher'])->group(function () {
    Route::get('/dashboard/teacher', [DashboardController::class, 'index'])
        ->name('dashboard.teacher');
});

// 🎓 Student
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/dashboard/student', [DashboardController::class, 'index'])
        ->name('dashboard.student');
});


Route::middleware('auth')->group(function () {
 
    // Profile (read-only) — admin, teacher, student
    Route::middleware('role:admin,teacher,student')->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    });
 
    // Profile (edit, update, destroy) — super_admin
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile/edit', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile/edit', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::prefix('admin')->group(function () {

        Route::get('/', [AdminController::class, 'index'])
            ->name('admin.index');

        Route::get('/archived', [AdminController::class, 'archived'])
            ->name('admin.archived');

        Route::put('/{slug}/restore', [AdminController::class, 'restore'])
            ->name('admin.restore');

        Route::get('/create', [AdminController::class, 'create'])
            ->name('admin.create');

        Route::post('/', [AdminController::class, 'store'])
            ->name('admin.store');

        Route::get('/{slug}/edit', [AdminController::class, 'edit'])
            ->name('admin.edit');

        Route::put('/{slug}', [AdminController::class, 'update'])
            ->name('admin.update');

        Route::delete('/{slug}', [AdminController::class, 'destroy'])
            ->name('admin.delete');

        Route::get('/{slug}', [AdminController::class, 'show'])
            ->name('admin.show');
    });
    Route::delete('/admin/{slug}/force-delete', [AdminController::class, 'forceDelete'])
    ->name('admin.forceDelete');
});

Route::middleware(['auth', 'role:super_admin,admin'])
    ->prefix('teachers')
    ->group(function () {

        // =====================
        // LIST DATA
        // =====================
        Route::get('/', [TeacherController::class, 'index'])
            ->name('teacher.index');

        // =====================
        // ARCHIVED PAGE
        // =====================
        Route::get('/archived', [TeacherController::class, 'archived'])
            ->name('teacher.archived');

        // =====================
        // CREATE & STORE
        // =====================
        Route::get('/create', [TeacherController::class, 'create'])
            ->name('teacher.create');

        Route::post('/', [TeacherController::class, 'store'])
            ->name('teacher.store');

        // =====================
        // RESTORE (ARCHIVED -> ACTIVE)
        // =====================
        Route::put('/restore/{slug}', [TeacherController::class, 'restore'])
            ->name('teacher.restore');

        // =====================
        // EDIT & UPDATE
        // =====================
        Route::get('/{slug}/edit', [TeacherController::class, 'edit'])
            ->name('teacher.edit');

        Route::put('/{slug}', [TeacherController::class, 'update'])
            ->name('teacher.update');

        // =====================
        // DETAIL
        // =====================
        Route::get('/{slug}', [TeacherController::class, 'show'])
            ->name('teacher.show');

        Route::delete('/{slug}', [TeacherController::class, 'destroy'])
            ->name('teacher.destroy');
    });

    Route::middleware(['auth', 'role:teacher'])->group(function () {

    Route::get(
        '/teacher/homeroom-students',
        [StudentController::class, 'homeroomStudents']
    )->name('teacher.homeroom.students');

});
Route::delete(
    '/teachers/{slug}/force-delete',
    [TeacherController::class, 'forceDelete']
)->name('teacher.forceDelete');

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->group(function () {

    Route::get('/students',[StudentController::class, 'index'])->name('students.index');

    Route::get('/students/create',[StudentController::class, 'create'])->name('students.create');

    Route::post('/students',[StudentController::class, 'store'])->name('students.store');

    Route::get('/students/archived',[StudentController::class, 'archived'])->name('students.archived');

    Route::put('/students/{slug}/restore',[StudentController::class, 'restore'])->name('students.restore');

    Route::get('/students/{student:slug}',[StudentController::class, 'show'])->name('students.show');

    Route::get('/students/{student:slug}/edit',[StudentController::class, 'edit'])->name('students.edit');

    Route::put('/students/{student:slug}',[StudentController::class, 'update'])->name('students.update');

    Route::delete('/students/{student:slug}',[StudentController::class, 'destroy'])->name('students.delete');

    Route::delete('/students/{slug}/force-delete', [StudentController::class, 'forceDelete'])->name('students.forceDelete');
});

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->group(function () {

    Route::get('/classes', [ClassesController::class, 'index'])
        ->name('class.index');

    Route::get('/classes/archived', [ClassesController::class, 'archived'])
        ->name('class.archived');

    Route::get('/classes/create', [ClassesController::class, 'create'])
        ->name('class.create');

    Route::post('/classes', [ClassesController::class, 'store'])
        ->name('class.store');

    Route::get('/classes/{class:slug}', [ClassesController::class, 'show'])
        ->name('class.show');

    Route::get('/classes/{class:slug}/edit', [ClassesController::class, 'edit'])
        ->name('class.edit');

    Route::put('/classes/{class:slug}', [ClassesController::class, 'update'])
        ->name('class.update');

    // archive (soft delete)
    Route::delete('/classes/{class:slug}', [ClassesController::class, 'destroy'])
        ->name('class.destroy');

    // restore
    Route::put('/classes/{slug}/restore', [ClassesController::class, 'restore'])
        ->name('class.restore');

    // hapus permanen
    Route::delete('/classes/{slug}/force-delete', [ClassesController::class, 'delete'])
        ->name('class.forceDelete');

});

Route::middleware(['auth', 'role:super_admin,admin,teacher,student'])
    ->prefix('schedule')
    ->group(function () {

        Route::get('/', [ScheduleController::class, 'index'])
            ->name('schedule.index');

        Route::get('/archived', [ScheduleController::class, 'archived'])
            ->name('schedule.archived');

        Route::get('/create', [ScheduleController::class, 'create'])
            ->name('schedule.create');

        Route::post('/', [ScheduleController::class, 'store'])
            ->name('schedule.store');

        Route::patch('/{id}/restore', [ScheduleController::class, 'restore'])
            ->name('schedule.restore');

        Route::get('/{id}', [ScheduleController::class, 'show'])
            ->name('schedule.show');

        Route::get('/{id}/edit', [ScheduleController::class, 'edit'])
            ->name('schedule.edit');

        Route::put('/{id}', [ScheduleController::class, 'update'])
            ->name('schedule.update');

        // archive
        Route::delete('/{id}', [ScheduleController::class, 'destroy'])
            ->name('schedule.destroy');

        // delete permanen
        Route::delete('/{id}/delete', [ScheduleController::class, 'delete'])
            ->name('schedule.delete');
    });

Route::middleware(['auth', 'role:super_admin,admin,teacher,student'])
    ->prefix('grades')
    ->group(function () {

        // index
        Route::get('/', [GradeController::class, 'index'])
            ->name('grades.index');

        // archived
        Route::get('/archived', [GradeController::class, 'archived'])
            ->name('grades.archived');

        // create
        Route::get('/create', [GradeController::class, 'create'])
            ->name('grades.create');

        // store
        Route::post('/store', [GradeController::class, 'store'])
            ->name('grades.store');

        // restore
        Route::put('/{id}/restore', [GradeController::class, 'restore'])
            ->name('grades.restore');

        // show
        Route::get('/{id}', [GradeController::class, 'show'])
            ->name('grades.show');

        // edit
        Route::get('/{id}/edit', [GradeController::class, 'edit'])
            ->name('grades.edit');

        // update
        Route::put('/{id}', [GradeController::class, 'update'])
            ->name('grades.update');

        // archive
        Route::delete('/{id}', [GradeController::class, 'destroy'])
            ->name('grades.destroy');

        // delete permanen
        Route::delete('/{id}/delete', [GradeController::class, 'delete'])
            ->name('grades.delete');
    });

Route::middleware(['auth', 'role:super_admin,admin,teacher,student'])->group(function () {
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

Route::middleware(['auth', 'role:super_admin,admin,teacher'])->group(function () {

    Route::get('/extracurricular', [ExtracurricularController::class, 'index'])
        ->name('extracurricular.index');

    // static routes WAJIB di atas route parameter
    Route::get('/extracurricular/archived', [ExtracurricularController::class, 'archived'])
        ->name('extracurricular.archived');

    Route::get('/extracurricular/create', [ExtracurricularController::class, 'create'])
        ->name('extracurricular.create');

    Route::post('/extracurricular/store', [ExtracurricularController::class, 'store'])
        ->name('extracurricular.store');

    // restore & force-delete WAJIB di atas route parameter slug biasa
    Route::put('/extracurricular/{slug}/restore', [ExtracurricularController::class, 'restore'])
        ->name('extracurricular.restore');

    Route::delete('/extracurricular/{slug}/force-delete', [ExtracurricularController::class, 'delete'])
        ->name('extracurricular.forceDelete');

    // route parameter
    Route::get('/extracurricular/{extracurricular:slug}', [ExtracurricularController::class, 'show'])
        ->name('extracurricular.show');

    Route::get('/extracurricular/{extracurricular:slug}/edit', [ExtracurricularController::class, 'edit'])
        ->name('extracurricular.edit');

    Route::put('/extracurricular/{extracurricular:slug}', [ExtracurricularController::class, 'update'])
        ->name('extracurricular.update');

    // archive (soft delete)
    Route::delete('/extracurricular/{extracurricular:slug}', [ExtracurricularController::class, 'destroy'])
        ->name('extracurricular.destroy');

});

Route::middleware(['auth', 'role:student'])->group(function () {

    Route::get('/student/extracurricular', [ExtracurricularController::class, 'studentExtracurricular'])
        ->name('student.extracurricular');

    Route::post('/extracurricular/{extracurricular:slug}/join', [ExtracurricularController::class, 'join'])
        ->name('extracurricular.join');
});

Route::middleware(['auth', 'role:super_admin,admin,teacher'])
    ->prefix('subjects')
    ->group(function () {

        Route::get('/', [SubjectController::class, 'index'])
            ->name('subjects.index');

        Route::get('/create', [SubjectController::class, 'create'])
            ->name('subjects.create');

        Route::post('/', [SubjectController::class, 'store'])
            ->name('subjects.store');

        Route::get('/archived', [SubjectController::class, 'archived'])
            ->name('subjects.archived');

        Route::get('/{slug}/edit', [SubjectController::class, 'edit'])
            ->name('subjects.edit');

        Route::put('/{slug}', [SubjectController::class, 'update'])
            ->name('subjects.update');

        // archive (soft delete)
        Route::delete('/{slug}', [SubjectController::class, 'destroy'])
            ->name('subjects.destroy');

        // restore dari archived
        Route::put('/{slug}/restore', [SubjectController::class, 'restore'])
            ->name('subjects.restore');

        // delete permanen
        Route::delete('/{slug}/delete', [SubjectController::class, 'delete'])
            ->name('subjects.delete');
    });

// Route::get('/test-rapot', function () {
//     return view('reports.pdf');
// })->name('reports.pdf');

Route::get(
    '/students/{slug}/raport',
    [ReportController::class, 'raport']
)->name('students.raport');

Route::get(
    '/students/{slug}/raport/print',
    [ReportController::class, 'print']
)->name('students.raport.print');

Route::get(
    '/students/{slug}/raport/download',
    [ReportController::class, 'downloadPdf']
)->name('students.raport.download');

Route::get(
    '/my-raport',
    [ReportController::class, 'myRaport']
)->name('student.my.raport');

Route::get(
    '/student/raport',
    [ReportController::class, 'studentRaport']
)->name('student.raport');