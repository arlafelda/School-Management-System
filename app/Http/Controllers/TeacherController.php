<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')
            ->where('archived', 0)
            ->latest()
            ->get();

        return view('teacher.teacher-index', compact('teachers'));
    }

    public function archived()
    {
        $teachers = Teacher::with('user')
            ->where('archived', 1)
            ->latest()
            ->get();

        return view('teacher.teacher-archived', compact('teachers'));
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'teacher',
            'archived' => 0,
        ]);

        $teacher = Teacher::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'archived' => 0,
        ]);

        $teacher->slug = Str::slug($request->name) . '-' . $teacher->id;
        $teacher->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil ditambahkan'
            ]);
        }

        return redirect()->route('teacher.index')
            ->with('success', 'Guru berhasil ditambahkan');
    }

    public function show(string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        return view('teacher.teacher-show', compact('teacher'));
    }

    public function edit(string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        return view('teacher.teacher-edit', compact('teacher'));
    }

    public function update(Request $request, string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:tbl_teachers,nip,' . $teacher->id,
            'subject' => 'nullable',
            'phone' => 'nullable',
            'address' => 'nullable',
            'position' => 'nullable',
        ]);

        $teacher->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'subject' => $request->subject,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
        ]);

        $teacher->user->update([
            'name' => $request->name,
        ]);

        $teacher->slug = Str::slug($request->name) . '-' . $teacher->id;
        $teacher->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil diupdate'
            ]);
        }

        return redirect()->route('teacher.index')
            ->with('success', 'Guru berhasil diupdate');
    }

    public function destroy(Request $request, string $slug)
    {
        $teacher = Teacher::where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        $teacher->update([
            'archived' => 1
        ]);

        if ($teacher->user) {
            $teacher->user->update([
                'archived' => 1
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dipindahkan ke arsip'
            ]);
        }

        return redirect()->route('teacher.index')
            ->with('success', 'Guru berhasil dipindahkan ke arsip');
    }
}