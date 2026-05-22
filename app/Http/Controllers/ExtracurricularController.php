<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $data = Extracurricular::with(['teacher', 'students'])
            ->where('archived', 0)
            ->latest()
            ->get();

        return view('extracurricular.extracurricular-index', compact('data'));
    }

    public function archived()
    {
        $data = Extracurricular::with(['teacher', 'students'])
            ->where('archived', 1)
            ->latest()
            ->get();

        return view('extracurricular.extracurricular-archived', compact('data'));
    }

    public function create()
    {
        $teachers = Teacher::where('archived', 0)->get();
        $students = Student::where('archived', 0)->get();

        return view('extracurricular.extracurricular-add', compact('teachers', 'students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'teacher_id' => 'nullable|exists:tbl_teachers,id',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

        while (Extracurricular::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $validated['slug'] = $slug;
        $validated['archived'] = 0;

        $ekskul = Extracurricular::create($validated);

        if ($request->student_ids) {
            $ekskul->students()->sync($request->student_ids);
        }

        return $request->ajax()
            ? response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $ekskul
            ])
            : redirect()->route('extracurricular.index')
                ->with('success', 'Data berhasil ditambahkan');
    }

    public function show(Extracurricular $extracurricular)
    {
        if ($extracurricular->archived == 1) {
            abort(404);
        }

        $extracurricular->load(['teacher', 'students']);

        return view('extracurricular.extracurricular-show', [
            'data' => $extracurricular
        ]);
    }

    public function edit(Extracurricular $extracurricular)
    {
        if ($extracurricular->archived == 1) {
            abort(404);
        }

        $teachers = Teacher::where('archived', 0)->get();
        $students = Student::where('archived', 0)->get();

        return view('extracurricular.extracurricular-edit', [
            'data' => $extracurricular,
            'teachers' => $teachers,
            'students' => $students
        ]);
    }

    public function update(Request $request, Extracurricular $extracurricular)
    {
        if ($extracurricular->archived == 1) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string',
            'teacher_id' => 'nullable|exists:tbl_teachers,id',
        ]);

        $baseSlug = Str::slug($request->name);
        $slug = $baseSlug;
        $counter = 1;

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

        return $request->ajax()
            ? response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate'
            ])
            : redirect()->route('extracurricular.index')
                ->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Request $request, Extracurricular $extracurricular)
    {
        $extracurricular->update([
            'archived' => 1
        ]);

        return $request->ajax()
            ? response()->json([
                'status' => true,
                'message' => 'Data berhasil dipindahkan ke arsip'
            ])
            : redirect()->route('extracurricular.index')
                ->with('success', 'Data berhasil dipindahkan ke arsip');
    }

    public function restore(string $slug)
    {
        $extracurricular = Extracurricular::where('slug', $slug)
            ->where('archived', 1)
            ->firstOrFail();

        $extracurricular->update([
            'archived' => 0
        ]);

        return redirect()
            ->route('extracurricular.archived')
            ->with('success', 'Data berhasil direstore');
    }

    public function delete(string $slug)
    {
        try {

            $extracurricular = Extracurricular::where('slug', $slug)
                ->where('archived', 1)
                ->firstOrFail();

            $extracurricular->delete();

            return redirect()
                ->route('extracurricular.archived')
                ->with('success', 'Data berhasil dihapus permanen');

        } catch (\Throwable $e) {

            return redirect()
                ->route('extracurricular.archived')
                ->with('error', $e->getMessage());
        }
    }

    public function studentExtracurricular()
    {
        $extracurriculars = Extracurricular::with('teacher')
            ->where('archived', 0)
            ->get();

        return view('extracurricular.extracurricular-student', compact('extracurriculars'));
    }

    public function join(Request $request, Extracurricular $extracurricular)
    {
        if ($extracurricular->archived == 1) {
            abort(404);
        }

        $student = Auth::user()->student;

        if (!$extracurricular->students()->where('student_id', $student->id)->exists()) {
            $extracurricular->students()->attach($student->id);
        }

        return $request->ajax()
            ? response()->json([
                'status' => true,
                'message' => 'Berhasil daftar ekskul'
            ])
            : back()->with('success', 'Berhasil daftar ekskul');
    }
}