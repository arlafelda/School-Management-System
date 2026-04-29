<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
    {
        $classes = ClassModel::all();

        $query = Grade::with(['student.class']);

        if ($request->class_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if ($request->major) {
            $query->whereHas('student.class', function ($q) use ($request) {
                $q->where('major', $request->major);
            });
        }

        if ($request->academic_year) {
            $query->whereHas('student.class', function ($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        if ($request->semester) {
            $query->whereHas('student.class', function ($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }

        $data = $query->latest()->get();

        return view('grade.grade-index', compact('data', 'classes'));
    }

    // =========================
    // CREATE
    // =========================
    public function create(Request $request)
    {
        $classes = ClassModel::all();
        $teachers = Teacher::all();

        $students = Student::with('class')

            // FILTER KELAS
            ->when($request->class_id, function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            })

            // FILTER JURUSAN
            ->when($request->major, function ($q) use ($request) {
                $q->whereHas('class', function ($q2) use ($request) {
                    $q2->where('major', $request->major);
                });
            })

            // FILTER TAHUN AJARAN
            ->when($request->academic_year, function ($q) use ($request) {
                $q->whereHas('class', function ($q2) use ($request) {
                    $q2->where('academic_year', $request->academic_year);
                });
            })

            // FILTER SEMESTER
            ->when($request->semester, function ($q) use ($request) {
                $q->whereHas('class', function ($q2) use ($request) {
                    $q2->where('semester', $request->semester);
                });
            })

            // 🔥 FILTER MAPEL (TEACHER)
            ->when($request->teacher_id, function ($q) use ($request) {
                $q->whereHas('class', function ($q2) use ($request) {
                    $q2->where('teacher_id', $request->teacher_id);
                });
            })

            ->get();

        return view('grade.grade-add', compact('students', 'classes', 'teachers'));
    }

    // =========================
    // STORE (FIX TOTAL)
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'student_id' => 'required|array',
        ]);

        foreach ($request->student_id as $index => $student_id) {

            Grade::create([
                'student_id' => $student_id,
                'subject' => $request->subject,
                'assignment_score' => $request->assignment_score[$index] ?? 0,
                'mid_exam_score' => $request->mid_exam_score[$index] ?? 0,
                'final_exam_score' => $request->final_exam_score[$index] ?? 0,
            ]);
        }

        return redirect()->route('grades.index')
            ->with('success', 'Nilai berhasil disimpan');
    }

    public function show($id)
    {
        $grade = Grade::with('student.class')->findOrFail($id);
         $teachers = Teacher::all();

        return view('grade.grade-show', compact('grade', 'teachers'));
    }

    public function edit($id)
    {
        $grade = Grade::findOrFail($id);

        return view('grade.grade-edit', compact('grade'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required',
        ]);

        $grade = Grade::findOrFail($id);

        $grade->update([
            'subject' => $request->subject,
            'assignment_score' => $request->assignment_score,
            'mid_exam_score' => $request->mid_exam_score,
            'final_exam_score' => $request->final_exam_score,
        ]);

        return redirect()->route('grades.index')->with('success', 'Nilai berhasil diupdate');
    }

    public function destroy($id)
    {
        $grade = Grade::findOrFail($id);

        $grade->delete();

        return redirect()->route('grades.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
