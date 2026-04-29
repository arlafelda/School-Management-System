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

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $ekskul = Extracurricular::create([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id
        ]);

        if ($request->student_ids) {
            $ekskul->students()->sync($request->student_ids);
        }

        return redirect()->route('extracurricular.index')->with('success', 'Data berhasil ditambahkan');
    }

    public function show($id)
    {
        $data = Extracurricular::with('teacher', 'students')->findOrFail($id);
        return view('extracurricular.extracurricular-show', compact('data'));
    }

    public function edit($id)
    {
        $data = Extracurricular::findOrFail($id);
        $teachers = Teacher::all();
        $students = Student::all();

        return view('extracurricular.extracurricular-edit', compact('data', 'teachers', 'students'));
    }

    public function update(Request $request, $id)
    {
        $data = Extracurricular::findOrFail($id);

        $data->update([
            'name' => $request->name,
            'teacher_id' => $request->teacher_id
        ]);

        $data->students()->sync($request->student_ids);

        return redirect()->route('extracurricular.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy($id)
    {
        Extracurricular::destroy($id);

        return redirect()->route('extracurricular.index')->with('success', 'Data berhasil dihapus');
    }

    public function studentExtracurricular()
    {
        $extracurriculars = Extracurricular::with('teacher')->get();

        return view('extracurricular.extracurricular-student', compact('extracurriculars'));
    }

    public function join($id)
    {
        $student = auth::user()->student;
        $ekskul = Extracurricular::findOrFail($id);

        // biar tidak double
        if (!$ekskul->students->contains($student->id)) {
            $ekskul->students()->attach($student->id);
        }

        return back()->with('success', 'Berhasil daftar ekskul');
    }
}
