<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClassesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role, ['super_admin', 'admin'])) {

            $classes = ClassModel::with(['students', 'teacher'])
                ->where('archived', 0)
                ->latest()
                ->get();

        } elseif ($user->role == 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            $classes = ClassModel::with(['students', 'teacher'])
                ->where('teacher_id', $teacher->id)
                ->where('archived', 0)
                ->latest()
                ->get();

        } else {
            abort(403);
        }

        return view('class.class-index', compact('classes'));
    }

    public function archived()
    {
        $classes = ClassModel::with(['students', 'teacher'])
            ->where('archived', 1)
            ->latest()
            ->get();

        return view('class.class-archived', compact('classes'));
    }

    public function create()
    {
        $teachers = Teacher::where('position', 'wali_kelas')
            ->where('archived', 0)
            ->get();

        return view('class.class-add', compact('teachers'));
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'name' => 'required',
                'level' => 'required',
                'major' => 'nullable',
                'academic_year' => 'required',
                'semester' => 'required',
                'teacher_id' => 'nullable|exists:tbl_teachers,id'
            ]);

            $baseSlug = Str::slug($request->name);
            $slug = $baseSlug;
            $counter = 1;

            while (ClassModel::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $validated['slug'] = $slug;
            $validated['archived'] = 0;

            $class = ClassModel::create($validated);

            if ($request->ajax()) {
                return response()->json([
                    'status' => true,
                    'message' => 'Kelas berhasil ditambahkan',
                    'data' => $class
                ]);
            }

            return redirect()
                ->route('class.index')
                ->with('success', 'Kelas berhasil ditambahkan');

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show(ClassModel $class)
    {
        if ($class->archived == 1) {
            abort(404);
        }

        $class->load(['teacher', 'students']);

        return view('class.class-show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        if ($class->archived == 1) {
            abort(404);
        }

        $teachers = Teacher::where('position', 'wali_kelas')
            ->where('archived', 0)
            ->get();

        return view('class.class-edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassModel $class)
    {
        if ($class->archived == 1) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required',
            'level' => 'required',
            'major' => 'nullable',
            'academic_year' => 'required',
            'semester' => 'required',
            'teacher_id' => 'nullable|exists:tbl_teachers,id'
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (
            ClassModel::where('slug', $slug)
                ->where('id', '!=', $class->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;

        $class->update($validated);

        return redirect()
            ->route('class.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function destroy(ClassModel $class)
    {
        $class->update([
            'archived' => 1
        ]);

        return redirect()
            ->route('class.index')
            ->with('success', 'Kelas berhasil dipindahkan ke arsip');
    }

    public function restore(string $slug)
    {
        $class = ClassModel::where('slug', $slug)
            ->where('archived', 1)
            ->firstOrFail();

        $class->update([
            'archived' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil direstore'
        ]);
    }
}