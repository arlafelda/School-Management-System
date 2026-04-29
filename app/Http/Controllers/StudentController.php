<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

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
            'name' => 'required',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6',

            'nisn' => 'required|unique:tbl_students,nisn',
            'nis' => 'required|unique:tbl_students,nis',

            'class_id' => 'required|exists:tbl_classes,id',
        ]);

        // USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student'
        ]);

        // STUDENT
        Student::create([
            'user_id' => $user->id,
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'name' => $request->name,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'major' => $request->major,
            'status' => $request->status ?? 'aktif',
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'phone' => $request->phone,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'parent_phone' => $request->parent_phone,
            'parent_address' => $request->parent_address,
        ]);

        // 🔥 CEK AJAX
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data siswa berhasil ditambahkan'
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Akun student berhasil dibuat');
    }

    public function show($id)
    {
        $student = Student::with('user', 'class')->findOrFail($id);
        return view('student.student-show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $classes = ClassModel::all(); // 🔥 WAJIB

        return view('student.student-edit', compact('student', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:tbl_users,email,' . $student->user_id,
            'nisn' => 'required|unique:tbl_students,nisn,' . $id,
            'nis' => 'required|unique:tbl_students,nis,' . $id,
            'class_id' => 'required|exists:tbl_classes,id',
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $student->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        $student->update([
            'name' => $request->name,
            'nisn' => $request->nisn,
            'nis' => $request->nis,
            'gender' => $request->gender,
            'class_id' => $request->class_id,
            'major' => $request->major,
            'status' => $request->status,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'address' => $request->address,
            'phone' => $request->phone,
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

    public function destroy($id, Request $request)
    {
        $student = Student::findOrFail($id);

        if ($student->user) {
            $student->user->delete();
        }

        $student->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }

        return redirect()->route('students.index')
            ->with('success', 'Data siswa berhasil dihapus');
    }
}
