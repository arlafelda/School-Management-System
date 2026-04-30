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
            ->when($request->class_id, fn($q) => $q->where('class_id', $request->class_id))
            ->when($request->major, function ($q) use ($request) {
                $q->whereHas('class', fn($q2) => $q2->where('major', $request->major));
            })
            ->when($request->academic_year, function ($q) use ($request) {
                $q->whereHas('class', fn($q2) => $q2->where('academic_year', $request->academic_year));
            })
            ->when($request->semester, function ($q) use ($request) {
                $q->whereHas('class', fn($q2) => $q2->where('semester', $request->semester));
            })
            ->when($request->teacher_id, function ($q) use ($request) {
                $q->whereHas('class', fn($q2) => $q2->where('teacher_id', $request->teacher_id));
            })
            ->get();

        return view('grade.grade-add', compact('students', 'classes', 'teachers'));
    }

    // =========================
    // STORE (AJAX READY)
    // =========================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string',
            'student_id' => 'required|array',
        ]);

        $created = [];

        foreach ($request->student_id as $index => $student_id) {
            $created[] = Grade::create([
                'student_id' => $student_id,
                'subject' => $request->subject,
                'assignment_score' => $request->assignment_score[$index] ?? 0,
                'mid_exam_score' => $request->mid_exam_score[$index] ?? 0,
                'final_exam_score' => $request->final_exam_score[$index] ?? 0,
            ]);
        }

        // ✅ AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil disimpan',
                'data' => $created
            ]);
        }

        return redirect()->route('grades.index')
            ->with('success', 'Nilai berhasil disimpan');
    }

    // =========================
    // SHOW
    // =========================
    public function show(int $id)
    {
        $grade = Grade::with(['student.class'])->findOrFail($id);
        $teachers = Teacher::all();

        return view('grade.grade-show', compact('grade', 'teachers'));
    }

    // =========================
    // EDIT
    // =========================
    public function edit(int $id)
    {
        $grade = Grade::findOrFail($id);

        // ambil subject unik saja
        $subjects = Teacher::select('subject')->distinct()->get();

        return view('grade.grade-edit', compact('grade', 'subjects'));
    }

    // =========================
    // UPDATE (AJAX READY)
    // =========================
    public function update(Request $request, int $id)
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

        // ✅ AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil diupdate'
            ]);
        }

        return redirect()->route('grades.index')
            ->with('success', 'Nilai berhasil diupdate');
    }

    // =========================
    // DELETE (AJAX READY)
    // =========================
    public function destroy(Request $request, int $id)
    {
        $grade = Grade::findOrFail($id);
        $grade->delete();

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }

        return redirect()->route('grades.index')
            ->with('success', 'Data berhasil dihapus');
    }
}
