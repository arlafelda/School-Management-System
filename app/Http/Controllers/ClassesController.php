<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Teacher;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class ClassesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ClassModel::with(['students', 'teacher']);

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

            $classes = $query->latest()->get();

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
        $classes = ClassModel::onlyTrashed()
            ->with(['students', 'teacher'])
            ->latest()
            ->get();

        return view('class.class-archived', compact('classes'));
    }

    public function create()
    {
        $teachers = Teacher::where('position', 'wali_kelas')->get();

        return view('class.class-add', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required',
            'level'         => 'required',
            'major'         => 'nullable',
            'academic_year' => 'required',
            'semester'      => 'required',
            'teacher_id'    => 'nullable|exists:tbl_teachers,id'
        ]);

        $baseSlug = Str::slug($request->name);
        $slug     = $baseSlug;
        $counter  = 1;

        while (ClassModel::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;

        $class = ClassModel::create($validated);

        // 📝 Catat aktivitas
        ActivityLogService::create(
            'Class',
            "Menambahkan kelas baru: {$class->name} (Level: {$class->level}, T.A: {$class->academic_year})",
            $class->name,
            $class->toArray()
        );

        return $request->ajax()
            ? response()->json([
                'status'  => true,
                'message' => 'Kelas berhasil ditambahkan',
                'data'    => $class,
            ])
            : redirect()
                ->route('class.index')
                ->with('success', 'Kelas berhasil ditambahkan');
    }

    public function show(ClassModel $class)
    {
        $class->load(['teacher', 'students']);

        return view('class.class-show', compact('class'));
    }

    public function edit(ClassModel $class)
    {
        $teachers = Teacher::where('position', 'wali_kelas')->get();

        return view('class.class-edit', compact('class', 'teachers'));
    }

    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'name'          => 'required',
            'level'         => 'required',
            'major'         => 'nullable',
            'academic_year' => 'required',
            'semester'      => 'required',
            'teacher_id'    => 'nullable|exists:tbl_teachers,id'
        ]);

        // Simpan data lama sebelum diupdate
        $oldData = $class->only(['name', 'level', 'major', 'academic_year', 'semester', 'teacher_id', 'slug']);

        $baseSlug = Str::slug($request->name);
        $slug     = $baseSlug;
        $counter  = 1;

        while (
            ClassModel::where('slug', $slug)
                ->where('id', '!=', $class->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;

        $class->update($validated);

        // 📝 Catat aktivitas
        ActivityLogService::update(
            'Class',
            "Mengupdate kelas: {$class->name} (Level: {$class->level}, T.A: {$class->academic_year})",
            $class->name,
            $oldData,
            $class->only(['name', 'level', 'major', 'academic_year', 'semester', 'teacher_id', 'slug'])
        );

        return redirect()
            ->route('class.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    // SOFT DELETE (arsip)
    public function destroy(ClassModel $class): JsonResponse
    {
        // 📝 Catat aktivitas sebelum dihapus
        ActivityLogService::delete(
            'Class',
            "Mengarsipkan kelas: {$class->name} (Level: {$class->level}, T.A: {$class->academic_year})",
            $class->name,
            $class->only(['name', 'level', 'major', 'academic_year', 'semester', 'slug'])
        );

        $class->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kelas dipindahkan ke arsip',
        ]);
    }

    // RESTORE
    public function restore(string $slug): JsonResponse
    {
        $class = ClassModel::onlyTrashed()
            ->where('slug', $slug)
            ->firstOrFail();

        $class->restore();

        // 📝 Catat aktivitas
        ActivityLogService::restore(
            'Class',
            "Merestore kelas: {$class->name} (Level: {$class->level}, T.A: {$class->academic_year})",
            $class->name
        );

        return response()->json([
            'success' => true,
            'message' => 'Kelas berhasil direstore',
        ]);
    }

    // FORCE DELETE (PERMANENT)
    public function delete(string $slug): JsonResponse
    {
        try {
            $class = ClassModel::onlyTrashed()
                ->where('slug', $slug)
                ->firstOrFail();

            // 📝 Catat aktivitas sebelum dihapus permanen
            ActivityLogService::forceDelete(
                'Class',
                "Menghapus permanen kelas: {$class->name} (Level: {$class->level}, T.A: {$class->academic_year})",
                $class->name,
                $class->only(['name', 'level', 'major', 'academic_year', 'semester', 'slug'])
            );

            $class->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus permanen',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}