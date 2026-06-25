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
            ->latest()
            ->get();

        return view('teacher.teacher-index', compact('teachers'));
    }

    public function archived()
    {
        $teachers = Teacher::onlyTrashed()
            ->with(['user', 'subjects'])
            ->latest()
            ->get();

        return view('teacher.teacher-archived', compact('teachers'));
    }

    public function create()
    {
        $subjects = Subject::all();

        return view('teacher.teacher-add', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required',
            'email'       => 'required|email|unique:tbl_users,email',
            'password'    => 'required|min:6',
            'nip'         => 'required|unique:tbl_teachers,nip',
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:tbl_subjects,id',
            'phone'       => 'nullable',
            'address'     => 'nullable',
            'position'    => 'nullable',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'teacher',
        ]);

        $teacher = Teacher::create([
            'user_id'  => $user->id,
            'name'     => $request->name,
            'nip'      => $request->nip,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'position' => $request->position,
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
            ->firstOrFail();

        return view('teacher.teacher-show', compact('teacher'));
    }

    public function edit(string $slug)
    {
        $teacher = Teacher::with(['user', 'subjects'])
            ->where('slug', $slug)
            ->firstOrFail();

        $subjects = Subject::all();

        return view('teacher.teacher-edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        $request->validate([
            'name'        => 'required',
            'nip'         => 'required|unique:tbl_teachers,nip,' . $teacher->id,
            'subject_ids' => 'nullable|array',
            'subject_ids.*' => 'exists:tbl_subjects,id',
            'phone'       => 'nullable',
            'address'     => 'nullable',
            'position'    => 'nullable',
            'password'    => 'nullable|min:6',
        ]);

        $teacher->update([
            'name'     => $request->name,
            'nip'      => $request->nip,
            'phone'    => $request->phone,
            'address'  => $request->address,
            'position' => $request->position,
        ]);

        $teacher->subjects()->sync($request->subject_ids ?? []);

        $teacher->user?->update(['name' => $request->name]);

        if ($request->filled('password')) {
            $teacher->user?->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()
            ->route('teacher.index')
            ->with('success', 'Guru berhasil diupdate');
    }

    // SOFT DELETE (arsip)
    public function destroy(Request $request, string $slug)
    {
        $teacher = Teacher::with('user')
            ->where('slug', $slug)
            ->firstOrFail();

        $teacher->user?->delete();
        $teacher->delete();

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

    // RESTORE
    public function restore(Request $request, string $slug)
    {
        $teacher = Teacher::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        if ($teacher->user_id) {
            User::onlyTrashed()->where('id', $teacher->user_id)->restore();
        }

        $teacher->restore();

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

    // FORCE DELETE (PERMANENT)
    public function forceDelete(string $slug)
    {
        $teacher = Teacher::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        $teacher->subjects()->detach();

        User::onlyTrashed()->where('id', $teacher->user_id)->forceDelete();

        $teacher->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Guru berhasil dihapus permanen'
        ]);
    }
}
