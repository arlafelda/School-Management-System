<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('user', 'class')->get();

        return view('student.student-index', compact('students'));
    }

    public function create()
    {
        $classes = ClassModel::all();

        return view('student.student-add', compact('classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6',
            'nisn'     => 'required|unique:tbl_students,nisn',
            'nis'      => 'required|unique:tbl_students,nis',
            'class_id' => 'required|exists:tbl_classes,id',
        ]);

        // USER
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'student',
        ]);

        // STUDENT
        $student = Student::create([
            'user_id'        => $user->id,
            'nisn'           => $request->nisn,
            'nis'            => $request->nis,
            'name'           => $request->name,
            'gender'         => $request->gender,
            'class_id'       => $request->class_id,
            'major'          => $request->major,
            'status'         => $request->status ?? 'aktif',
            'birth_place'    => $request->birth_place,
            'birth_date'     => $request->birth_date,
            'address'        => $request->address,
            'phone'          => $request->phone,
            'father_name'    => $request->father_name,
            'mother_name'    => $request->mother_name,
            'parent_phone'   => $request->parent_phone,
            'parent_address' => $request->parent_address,
        ]);

        // 📝 Catat aktivitas
        $className = $student->class?->name ?? '-';

        ActivityLogService::create(
            'Student',
            "Menambahkan siswa baru: {$student->name} (NISN: {$student->nisn}, Kelas: {$className})",
            $student->name,
            $student->only([
                'name', 'nisn', 'nis', 'gender',
                'class_id', 'major', 'status', 'email',
            ])
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil ditambahkan',
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Akun student berhasil dibuat');
    }

    public function show(string $slug)
    {
        $student = Student::with('user', 'class')
            ->where('slug', $slug)
            ->firstOrFail();

        return view('student.student-show', compact('student'));
    }

    public function edit(string $slug)
    {
        $student = Student::where('slug', $slug)->firstOrFail();
        $classes = ClassModel::all();

        return view('student.student-edit', compact('student', 'classes'));
    }

    public function update(Request $request, string $slug)
    {
        $student = Student::with('user', 'class')
            ->where('slug', $slug)
            ->firstOrFail();

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:tbl_users,email,' . $student->user_id,
            'nisn'     => 'required|unique:tbl_students,nisn,' . $student->id,
            'nis'      => 'required|unique:tbl_students,nis,' . $student->id,
            'class_id' => 'required|exists:tbl_classes,id',
        ]);

        // Simpan data lama sebelum diupdate
        $oldData = array_merge(
            $student->only([
                'name', 'nisn', 'nis', 'gender',
                'class_id', 'major', 'status',
                'birth_place', 'birth_date', 'address', 'phone',
            ]),
            ['email' => $student->user?->email]
        );

        $student->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $student->user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $student->update([
            'name'        => $request->name,
            'nisn'        => $request->nisn,
            'nis'         => $request->nis,
            'gender'      => $request->gender,
            'class_id'    => $request->class_id,
            'major'       => $request->major,
            'status'      => $request->status,
            'birth_place' => $request->birth_place,
            'birth_date'  => $request->birth_date,
            'address'     => $request->address,
            'phone'       => $request->phone,
        ]);

        // 📝 Catat aktivitas
        $className = $student->class?->name ?? '-';

        ActivityLogService::update(
            'Student',
            "Mengupdate data siswa: {$student->name} (NISN: {$student->nisn}, Kelas: {$className})",
            $student->name,
            $oldData,
            array_merge(
                $student->only([
                    'name', 'nisn', 'nis', 'gender',
                    'class_id', 'major', 'status',
                    'birth_place', 'birth_date', 'address', 'phone',
                ]),
                ['email' => $request->email]
            )
        );

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diupdate',
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil diupdate');
    }

    // SOFT DELETE (arsip)
    public function destroy(string $slug, Request $request)
    {
        $student = Student::with('user', 'class')
            ->where('slug', $slug)
            ->firstOrFail();

        // 📝 Catat aktivitas sebelum dihapus
        $className = $student->class?->name ?? '-';

        ActivityLogService::delete(
            'Student',
            "Mengarsipkan siswa: {$student->name} (NISN: {$student->nisn}, Kelas: {$className})",
            $student->name,
            $student->only(['name', 'nisn', 'nis', 'class_id', 'status'])
        );

        // Soft delete user terkait
        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dipindahkan ke arsip',
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Data berhasil dipindahkan ke arsip');
    }

    // HALAMAN ARSIP
    public function archived()
    {
        $students = Student::onlyTrashed()->with('user', 'class')->get();

        return view('student.student-archived', compact('students'));
    }

    // RESTORE
    public function restore(string $slug)
    {
        $student = Student::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        // Restore user terkait
        if ($student->user_id) {
            User::onlyTrashed()->where('id', $student->user_id)->restore();
        }

        $student->restore();

        // 📝 Catat aktivitas
        ActivityLogService::restore(
            'Student',
            "Merestore siswa: {$student->name} (NISN: {$student->nisn})",
            $student->name
        );

        return redirect()
            ->route('students.archived')
            ->with('success', 'Data berhasil direstore');
    }

    // FORCE DELETE (PERMANENT)
    public function forceDelete(string $slug)
    {
        try {
            $student = Student::onlyTrashed()
                ->where('slug', $slug)
                ->firstOrFail();

            // 📝 Catat aktivitas sebelum dihapus permanen
            ActivityLogService::forceDelete(
                'Student',
                "Menghapus permanen siswa: {$student->name} (NISN: {$student->nisn})",
                $student->name,
                $student->only(['name', 'nisn', 'nis', 'class_id', 'status'])
            );

            // Hapus permanen user terkait
            if ($student->user_id) {
                User::onlyTrashed()->where('id', $student->user_id)->forceDelete();
            }

            $student->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus permanen',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function homeroomStudents()
    {
        $user = Auth::user();

        if (!$user || !$user->teacher) {
            abort(403, 'Akun teacher tidak ditemukan.');
        }

        $teacher = $user->teacher;
        $class   = ClassModel::where('teacher_id', $teacher->id)->first();

        if (!$class) {
            abort(403, 'Anda bukan wali kelas.');
        }

        $students = Student::with(['user', 'class'])
            ->where('class_id', $class->id)
            ->get();

        return view('student.student-index', compact('students'));
    }
}