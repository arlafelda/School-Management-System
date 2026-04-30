<?php

namespace App\Http\Controllers;

use App\Models\Extracurricular;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtracurricularController extends Controller
{
    public function index()
    {
        $data = Extracurricular::with('teacher')->latest()->get();
        return view('extracurricular.extracurricular-index', compact('data'));
    }

    public function create()
    {
        $teachers = Teacher::all();
        $students = Student::all();

        return view('extracurricular.extracurricular-add', compact('teachers', 'students'));
    }

    /* =========================
       STORE (AJAX READY)
    ========================= */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'teacher_id' => 'nullable'
        ]);

        $ekskul = Extracurricular::create($validated);

        if ($request->student_ids) {
            $ekskul->students()->sync($request->student_ids);
        }

        // ✅ AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'data' => $ekskul
            ]);
        }

        return redirect()->route('extracurricular.index')
            ->with('success', 'Data berhasil ditambahkan');
    }

    public function show(Request $request, int $id)
    {
        $data = Extracurricular::with(['teacher', 'students'])->findOrFail($id);

        return view('extracurricular.extracurricular-show', compact('data'));
    }

    public function edit(int $id)
    {
        $data = Extracurricular::findOrFail($id);
        $teachers = Teacher::all();
        $students = Student::all();

        return view('extracurricular.extracurricular-edit', compact('data', 'teachers', 'students'));
    }

    /* =========================
       UPDATE (AJAX READY)
    ========================= */
    public function update(Request $request, int $id)
    {
        $data = Extracurricular::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'teacher_id' => 'nullable'
        ]);

        $data->update($validated);

        if ($request->student_ids) {
            $data->students()->sync($request->student_ids);
        }

        // ✅ AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diupdate',
                'data' => $data
            ]);
        }

        return redirect()->route('extracurricular.index')
            ->with('success', 'Data berhasil diupdate');
    }

    /* =========================
       DELETE (AJAX READY)
    ========================= */
    public function destroy(Request $request, int $id)
    {
        Extracurricular::destroy($id);

        // ✅ AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }

        return redirect()->route('extracurricular.index')
            ->with('success', 'Data berhasil dihapus');
    }

    public function studentExtracurricular()
    {
        $extracurriculars = Extracurricular::with('teacher')->get();

        return view('extracurricular.extracurricular-student', compact('extracurriculars'));
    }

    /* =========================
       JOIN (AJAX READY)
    ========================= */
    public function join(Request $request, int $id)
    {
        $student = Auth::user()->student;
        $ekskul = Extracurricular::findOrFail($id);

        if (!$ekskul->students->contains($student->id)) {
            $ekskul->students()->attach($student->id);
        }

        // ✅ AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Berhasil daftar ekskul'
            ]);
        }

        return back()->with('success', 'Berhasil daftar ekskul');
    }
}
