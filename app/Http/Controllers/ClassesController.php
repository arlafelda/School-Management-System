<?php

namespace App\Http\Controllers;

use App\Models\ClassModel;
use App\Models\Teacher;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function index()
    {
        $classes = ClassModel::with(['students', 'teacher'])->latest()->get();
        return view('class.class-index', compact('classes'));
    }

    public function create()
    {
        $teachers = Teacher::where('position', 'wali_kelas')->get();
        return view('class.class-add', compact('teachers'));
    }

    /* =========================
       STORE (AJAX READY)
    ========================= */
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

        $class = ClassModel::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Kelas berhasil ditambahkan',
                'data' => $class
            ]);
        }

        return redirect()->route('class.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $class = ClassModel::findOrFail($id);
        $teachers = Teacher::where('position', 'wali_kelas')->get();

        return view('class.class-edit', compact('class', 'teachers'));
    }

    /* =========================
       UPDATE (AJAX READY)
    ========================= */
    public function update(Request $request, int $id)
    {
        $class = ClassModel::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required',
            'level' => 'required',
            'major' => 'nullable',
            'academic_year' => 'required',
            'semester' => 'required',
            'teacher_id' => 'nullable|exists:tbl_teachers,id'
        ]);

        $class->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Kelas berhasil diupdate',
                'data' => $class
            ]);
        }

        return redirect()->route('class.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function show(int $id, Request $request)
    {
        $class = ClassModel::with(['teacher', 'students'])->findOrFail($id);

        $year = $request->year ?? '2023/2024';
        $semester = $request->semester ?? 'Ganjil';

        return view('class.class-show', compact('class', 'year', 'semester'));
    }

    /* =========================
       DELETE (AJAX READY)
    ========================= */
    public function destroy(Request $request, int $id)
    {
        ClassModel::destroy($id);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Kelas berhasil dihapus'
            ]);
        }

        return redirect()->route('class.index')
            ->with('success', 'Kelas berhasil dihapus');
    }
}
