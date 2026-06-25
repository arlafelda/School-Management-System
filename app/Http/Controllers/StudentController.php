<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
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
        $validated = $request->validate([
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
        Student::create([
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

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil ditambahkan'
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
        $student = Student::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        $validated = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:tbl_users,email,' . $student->user_id,
            'nisn'     => 'required|unique:tbl_students,nisn,' . $student->id,
            'nis'      => 'required|unique:tbl_students,nis,' . $student->id,
            'class_id' => 'required|exists:tbl_classes,id',
        ]);

        $student->user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $student->user->update([
                'password' => Hash::make($request->password)
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

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil diupdate'
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil diupdate');
    }

    // SOFT DELETE (arsip)
    public function destroy(string $slug, Request $request)
    {
        $student = Student::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        // Soft delete user terkait
        if ($student->user) {
            $student->user->delete();
        }

        // Soft delete student
        $student->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dipindahkan ke arsip'
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

            // Hapus permanen user terkait
            if ($student->user_id) {
                User::onlyTrashed()->where('id', $student->user_id)->forceDelete();
            }

            // Hapus permanen student
            $student->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil dihapus permanen'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen',
                'error'   => $e->getMessage()
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

        $class = ClassModel::where('teacher_id', $teacher->id)->first();

        if (!$class) {
            abort(403, 'Anda bukan wali kelas.');
        }

        $students = Student::with(['user', 'class'])
            ->where('class_id', $class->id)
            ->get();

        return view('student.student-index', compact('students'));
    }
}
