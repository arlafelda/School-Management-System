<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ClassModel::with(['students', 'teacher'])
            ->where('archived', 0);

        // FILTER SEARCH
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // FILTER LEVEL
        if ($request->level) {
            $query->where('level', $request->level);
        }

        // FILTER MAJOR
        if ($request->major) {
            $query->where('major', 'like', '%' . $request->major . '%');
        }

        if (in_array($user->role, ['super_admin', 'admin'])) {

            $classes = $query
                ->latest()
                ->get();

        } elseif ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            $classes = $query
                ->when($teacher, fn($q) => $q->where('teacher_id', $teacher->id))
                ->latest()
                ->get();

        } else {
            abort(403, 'Akses ditolak');
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

        return $request->ajax()
            ? response()->json([
                'status' => true,
                'message' => 'Kelas berhasil ditambahkan',
                'data' => $class
            ])
            : redirect()
                ->route('class.index')
                ->with('success', 'Kelas berhasil ditambahkan');
    }

    public function show(ClassModel $class)
    {
        abort_if($class->archived == 1, 404);

        $class->load(['teacher', 'students']);

        return view('class.class-show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        abort_if($class->archived == 1, 404);

        $teachers = Teacher::where('position', 'wali_kelas')
            ->where('archived', 0)
            ->get();

        return view('class.class-edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassModel $class)
    {
        abort_if($class->archived == 1, 404);

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

    public function destroy(ClassModel $class): JsonResponse
    {
        $class->update(['archived' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas dipindahkan ke arsip'
        ]);
    }

    public function restore(string $slug): JsonResponse
    {
        $class = ClassModel::where('slug', $slug)
            ->where('archived', 1)
            ->firstOrFail();

        $class->update(['archived' => 0]);

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil direstore'
        ]);
    }

    public function delete(string $slug): JsonResponse
    {
        try {

            $class = ClassModel::where('slug', $slug)
                ->where('archived', 1)
                ->firstOrFail();

            $class->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus permanen'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}