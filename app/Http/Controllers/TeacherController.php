<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with(['user', 'subjects'])
            ->where('archived', 0)
            ->latest()
            ->get();

        return view('teacher.teacher-index', compact('teachers'));
    }

    public function archived()
    {
        $teachers = Teacher::with(['user', 'subjects'])
            ->where('archived', 1)
            ->latest()
            ->get();

        return view('teacher.teacher-archived', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::where('archived', 0)->get();

        return view('teacher.teacher-add', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6',
            'nip' => 'required|unique:tbl_teachers,nip',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:tbl_subjects,id',
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
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
            'archived' => 0,
        ]);

        $teacher->subjects()->sync($request->subject_ids ?? []);

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Guru berhasil ditambahkan');
    }

    public function show(string $slug)
    {
        $teacher = Teacher::with(['user', 'subjects'])
            ->where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        return view('teacher.teacher-show', compact('teacher'));
    }

    public function edit(string $slug)
    {
        $teacher = Teacher::with(['user', 'subjects'])
            ->where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        $subjects = Subject::where('archived', 0)->get();

        return view('teacher.teacher-edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        $request->validate([
            'name' => 'required',
            'nip' => 'required|unique:tbl_teachers,nip,' . $teacher->id,
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:tbl_subjects,id',
            'phone' => 'nullable',
            'address' => 'nullable',
            'position' => 'nullable',
            'password' => 'nullable|min:6', // ✅ TAMBAHAN
        ]);

        // UPDATE TEACHER
        $teacher->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'phone' => $request->phone,
            'address' => $request->address,
            'position' => $request->position,
        ]);

        // SYNC SUBJECT
        $teacher->subjects()->sync($request->subject_ids ?? []);

        // UPDATE USER NAME
        $teacher->user?->update([
            'name' => $request->name
        ]);

        // =========================
        // UPDATE PASSWORD (OPSIONAL)
        // =========================
        if ($request->filled('password')) {
            $teacher->user?->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Guru berhasil diupdate');
    }

    public function destroy(Request $request, string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        $teacher->update([
            'archived' => 1
        ]);

        $teacher->user?->update([
            'archived' => 1
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil dipindahkan ke arsip'
            ]);
        }

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Guru berhasil dipindahkan ke arsip');
    }

    public function restore(Request $request, string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->where('archived', 1)
            ->firstOrFail();

        $teacher->update([
            'archived' => 0
        ]);

        $teacher->user?->update([
            'archived' => 0
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Guru berhasil direstore'
            ]);
        }

        return redirect()
            ->route('teacher.archived')
            ->with('success', 'Guru berhasil direstore');
    }

    public function forceDelete(string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->where('archived', 1)
            ->firstOrFail();

        // Hapus relasi many-to-many
        $teacher->subjects()->detach();

        // Hapus user terkait
        $teacher->user?->delete();

        // Hapus guru
        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dihapus permanen'
        ]);
    }
}
