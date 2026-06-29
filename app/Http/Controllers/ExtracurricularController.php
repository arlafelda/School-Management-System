<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Teacher;
use App\Models\Student;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $data = Extracurricular::with(['teacher', 'students'])
            ->latest()
            ->get();

        return view('extracurricular.extracurricular-index', compact('data'));
    }

    public function archived()
    {
        $data = Extracurricular::onlyTrashed()
            ->with(['teacher', 'students'])
            ->latest()
            ->get();

        return view('extracurricular.extracurricular-archived', compact('data'));
    }

    public function create()
    {
        $teachers = Teacher::all();
        $students = Student::all();

        return view('extracurricular.extracurricular-add', compact('teachers', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'teacher_id' => 'nullable|exists:tbl_teachers,id',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug     = $baseSlug;
        $counter  = 1;

        while (Extracurricular::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;

        $ekskul = Extracurricular::create($validated);

        if ($request->student_ids) {
            $ekskul->students()->sync($request->student_ids);
        }

        // 📝 Catat aktivitas
        $teacherName   = $ekskul->teacher?->name ?? '-';
        $studentCount  = count($request->student_ids ?? []);

        ActivityLogService::create(
            'Extracurricular',
            "Menambahkan ekskul baru: {$ekskul->name} — Pembina: {$teacherName}, Anggota: {$studentCount} siswa",
            $ekskul->name,
            $ekskul->toArray()
        );

        return $request->ajax()
            ? response()->json([
                'status'  => true,
                'message' => 'Data berhasil ditambahkan',
                'data'    => $ekskul,
            ])
            : redirect()->route('extracurricular.index')
                ->with('success', 'Data berhasil ditambahkan');
    }

    public function show(Extracurricular $extracurricular)
    {
        $extracurricular->load(['teacher', 'students']);

        return view('extracurricular.extracurricular-show', [
            'data' => $extracurricular,
        ]);
    }

    public function edit(Extracurricular $extracurricular)
    {
        $teachers = Teacher::all();
        $students = Student::all();

        return view('extracurricular.extracurricular-edit', [
            'data'     => $extracurricular,
            'teachers' => $teachers,
            'students' => $students,
        ]);
    }

    public function update(Request $request, Extracurricular $extracurricular)
    {
        $validated = $request->validate([
            'name'       => 'required|string',
            'teacher_id' => 'nullable|exists:tbl_teachers,id',
        ]);

        // Simpan data lama sebelum diupdate
        $oldData = $extracurricular->only(['name', 'teacher_id', 'slug']);
        $oldStudentIds = $extracurricular->students()->pluck('student_id')->toArray();

        $baseSlug = Str::slug($request->name);
        $slug     = $baseSlug;
        $counter  = 1;

        while (
            Extracurricular::where('slug', $slug)
                ->where('id', '!=', $extracurricular->id)
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;

        $extracurricular->update($validated);

        if ($request->student_ids) {
            $extracurricular->students()->sync($request->student_ids);
        }

        // 📝 Catat aktivitas
        $teacherName = $extracurricular->teacher?->name ?? '-';

        ActivityLogService::update(
            'Extracurricular',
            "Mengupdate ekskul: {$extracurricular->name} — Pembina: {$teacherName}",
            $extracurricular->name,
            array_merge($oldData, ['student_ids' => $oldStudentIds]),
            array_merge(
                $extracurricular->only(['name', 'teacher_id', 'slug']),
                ['student_ids' => $request->student_ids ?? []]
            )
        );

        return $request->ajax()
            ? response()->json([
                'status'  => true,
                'message' => 'Data berhasil diupdate',
            ])
            : redirect()->route('extracurricular.index')
                ->with('success', 'Data berhasil diupdate');
    }

    // SOFT DELETE (arsip)
    public function destroy(Request $request, Extracurricular $extracurricular)
    {
        // 📝 Catat aktivitas sebelum dihapus
        $teacherName = $extracurricular->teacher?->name ?? '-';

        ActivityLogService::delete(
            'Extracurricular',
            "Mengarsipkan ekskul: {$extracurricular->name} — Pembina: {$teacherName}",
            $extracurricular->name,
            $extracurricular->only(['name', 'teacher_id', 'slug'])
        );

        $extracurricular->delete();

        return $request->expectsJson()
            ? response()->json([
                'success' => true,
                'message' => 'Data berhasil dipindahkan ke arsip',
            ])
            : redirect()->route('extracurricular.index')
                ->with('success', 'Data berhasil dipindahkan ke arsip');
    }

    // RESTORE
    public function restore(string $slug)
    {
        try {
            $extracurricular = Extracurricular::onlyTrashed()
                ->where('slug', $slug)
                ->firstOrFail();

            $extracurricular->restore();

            // 📝 Catat aktivitas
            ActivityLogService::restore(
                'Extracurricular',
                "Merestore ekskul: {$extracurricular->name}",
                $extracurricular->name
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil direstore',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // FORCE DELETE (PERMANENT)
    public function delete(string $slug)
    {
        try {
            $extracurricular = Extracurricular::onlyTrashed()
                ->where('slug', $slug)
                ->firstOrFail();

            // 📝 Catat aktivitas sebelum dihapus permanen
            ActivityLogService::forceDelete(
                'Extracurricular',
                "Menghapus permanen ekskul: {$extracurricular->name}",
                $extracurricular->name,
                $extracurricular->only(['name', 'teacher_id', 'slug'])
            );

            $extracurricular->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus permanen',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function studentExtracurricular()
    {
        $extracurriculars = Extracurricular::with('teacher')->get();

        return view('extracurricular.extracurricular-student', compact('extracurriculars'));
    }

    public function join(Request $request, Extracurricular $extracurricular)
    {
        $student = Auth::user()->student;

        if (!$extracurricular->students()->where('student_id', $student->id)->exists()) {
            $extracurricular->students()->attach($student->id);

            // 📝 Catat aktivitas
            ActivityLogService::create(
                'Extracurricular',
                "Siswa mendaftar ekskul: {$extracurricular->name} — Siswa: {$student->name}",
                $student->name
            );
        }

        return $request->ajax()
            ? response()->json([
                'status'  => true,
                'message' => 'Berhasil daftar ekskul',
            ])
            : back()->with('success', 'Berhasil daftar ekskul');
    }
}