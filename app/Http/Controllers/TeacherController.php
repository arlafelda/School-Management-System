<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->latest()->get();
        return view('teacher.teacher-index', compact('teachers'));
    }

    public function create()
    {
        return view('teacher.teacher-add');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6',
            'nip' => 'required|unique:tbl_teachers,nip',
            'subject' => 'nullable',
            'phone' => 'nullable',
            'address' => 'nullable',
            'position' => 'nullable',
        ]);

        // USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
        ]);

        // TEACHER
        Teacher::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
        ]);

        // 🔥 AJAX RESPONSE
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditambahkan'
            ]);
        }

        return redirect()->route('teacher.index')
            ->with('success', 'Guru berhasil ditambahkan');
    }

    public function show($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('teacher.teacher-show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        return view('teacher.teacher-edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:tbl_teachers,nip,' . $id,
            'subject' => 'nullable',
            'phone' => 'nullable',
            'address' => 'nullable',
            'position' => 'nullable',
        ]);

        // UPDATE TEACHER
        $teacher->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
        ]);

        // UPDATE USER
        $teacher->user->update([
            'name' => $request->name,
        ]);

        // 🔥 AJAX RESPONSE
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil diupdate'
            ]);
        }

        return redirect()->route('teacher.index')
            ->with('success', 'Guru berhasil diupdate');
    }

    public function destroy(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        if ($teacher->user) {
            $teacher->user->delete();
        }

        $teacher->delete();

        // 🔥 AJAX RESPONSE
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dihapus'
            ]);
        }

        return redirect()->route('teacher.index')
            ->with('success', 'Guru berhasil dihapus');
    }
}