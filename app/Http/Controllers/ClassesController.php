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
        $teachers = Teacher::all();
        return view('class.class-add', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'level' => 'required',
            'major' => 'nullable',
            'academic_year' => 'required',
            'semester' => 'required',
            'teacher_id' => 'nullable'
        ]);

        ClassModel::create([
            'name' => $request->name,
            'level' => $request->level,
            'major' => $request->major,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'teacher_id' => $request->teacher_id
        ]);

        return redirect()->route('class.index')
            ->with('success', 'Kelas berhasil ditambahkan');
    }
    public function edit($id)
    {
        $class = ClassModel::findOrFail($id);
        $teachers = Teacher::all();

        return view('class.class-edit', compact('class', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $class = ClassModel::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'level' => 'required',
            'major' => 'nullable',
            'academic_year' => 'required',
            'semester' => 'required',
            'teacher_id' => 'nullable'
        ]);

        $class->update([
            'name' => $request->name,
            'level' => $request->level,
            'major' => $request->major,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'teacher_id' => $request->teacher_id
        ]);

        return redirect()->route('class.index')
            ->with('success', 'Kelas berhasil diupdate');
    }

    public function show($id, Request $request)
    {
        $class = ClassModel::with(['teacher', 'students'])->findOrFail($id);

        $year = $request->year ?? '2023/2024';
        $semester = $request->semester ?? 'Ganjil';

        return view('class.class-show', compact('class', 'year', 'semester'));
    }

    public function destroy($id)
    {
        ClassModel::destroy($id);

        return redirect()->route('class.index')
            ->with('success', 'Kelas berhasil dihapus');
    }
}
