<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    // =========================
    // INDEX
    // =========================
    public function index(Request $request)
    {
        $user = Auth::user();
        $classes = ClassModel::where('archived', 0)->get();

        $query = Grade::with([
            'student.class',
            'schedule.teacher',
            'schedule.class'
        ])->where('archived', 0);

        // =========================
        // STUDENT
        // =========================
        if ($user->role === 'student') {

            $student = $user->student;

            if (!$student) {
                abort(403, 'Data student tidak ditemukan');
            }

            $query->where('student_id', $student->id);
        }

        // =========================
        // TEACHER
        // =========================
        elseif ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {
                $scheduleIds = Schedule::where('teacher_id', $teacher->id)
                    ->where('archived', 0)
                    ->pluck('id');

                $query->whereIn('schedule_id', $scheduleIds);
            }
        }

        // =========================
        // FILTER CLASS
        // =========================
        if ($request->class_id) {
            $query->whereHas('student', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        // =========================
        // FILTER MAJOR
        // =========================
        if ($request->major) {
            $query->whereHas('student.class', function ($q) use ($request) {
                $q->where('major', $request->major);
            });
        }

        // =========================
        // FILTER ACADEMIC YEAR
        // =========================
        if ($request->academic_year) {
            $query->whereHas('student.class', function ($q) use ($request) {
                $q->where('academic_year', $request->academic_year);
            });
        }

        // =========================
        // FILTER SEMESTER
        // =========================
        if ($request->semester) {
            $query->whereHas('student.class', function ($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }

        $data = $query->latest()->get();

        return view('grade.grade-index', compact('data', 'classes'));
    }

    // =========================
    // ARCHIVED
    // =========================
    public function archived()
{
    $grades = Grade::with([
        'student.class',
        'schedule.teacher',
        'schedule.class'
    ])
    ->where('archived', 1)
    ->latest()
    ->get();

    return view('grade.grade-archived', compact('grades'));
}

    // =========================
    // RESTORE
    // =========================
    public function restore(int $id, Request $request)
    {
        $grade = Grade::where('archived', 1)
            ->findOrFail($id);

        $grade->update([
            'archived' => 0
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data nilai berhasil direstore'
            ]);
        }

        return redirect()
            ->route('grades.archived')
            ->with('success', 'Data nilai berhasil direstore');
    }

    // =========================
    // CREATE
    // =========================
    public function create(Request $request)
    {
        $user = Auth::user();

        $classes = ClassModel::where('archived', 0)->get();
        $teachers = Teacher::where('archived', 0)->get();

        $schedules = collect();
        $students = collect();

        $selectedTeacher = Teacher::where('user_id', $user->id)->first();

        if ($user->role === 'teacher') {

            if (!$selectedTeacher) {
                return redirect()
                    ->route('grades.index')
                    ->with('error', 'Teacher tidak ditemukan');
            }

            $schedules = Schedule::with(['class', 'teacher'])
                ->where('teacher_id', $selectedTeacher->id)
                ->where('archived', 0)
                ->get();

            $classIds = $schedules->pluck('class_id')->unique();

            $students = Student::with('class')
                ->where('archived', 0)
                ->whereIn('class_id', $classIds)
                ->get();
        }

        else {

            $schedules = Schedule::with(['class', 'teacher'])
                ->where('archived', 0)
                ->get();

            $students = Student::with('class')
                ->where('archived', 0)
                ->when($request->class_id, function ($q) use ($request) {
                    $q->where('class_id', $request->class_id);
                })
                ->get();
        }

        return view('grade.grade-add', compact(
            'classes',
            'teachers',
            'schedules',
            'students'
        ));
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:tbl_schedules,id',
            'student_id' => 'required|array',
            'subject' => 'required|string',
        ]);

        foreach ($request->student_id as $i => $student_id) {

            Grade::create([
                'schedule_id' => $request->schedule_id,
                'student_id' => $student_id,
                'subject' => $request->subject,
                'assignment_score' => $request->assignment_score[$i] ?? 0,
                'mid_exam_score' => $request->mid_exam_score[$i] ?? 0,
                'final_exam_score' => $request->final_exam_score[$i] ?? 0,
                'archived' => 0
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Nilai berhasil disimpan'
        ]);
    }

    // =========================
    // SHOW
    // =========================
    public function show(int $id)
    {
        $grade = Grade::with([
            'student.class',
            'schedule.teacher',
            'schedule.class'
        ])->findOrFail($id);

        if ($grade->archived == 1) {
            abort(404);
        }

        return view('grade.grade-show', compact('grade'));
    }

    // =========================
    // EDIT
    // =========================
    public function edit(int $id)
    {
        $user = Auth::user();

        $grade = Grade::with([
            'student.class',
            'schedule.teacher'
        ])->findOrFail($id);

        if ($grade->archived == 1) {
            abort(404);
        }

        $subjects = collect();

        if ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {
                $subjects = collect([
                    (object)[
                        'subject' => $teacher->subject
                    ]
                ]);
            }
        }

        else {
            $subjects = Teacher::select('subject')
                ->where('archived', 0)
                ->distinct()
                ->orderBy('subject')
                ->get();
        }

        return view('grade.grade-edit', compact(
            'grade',
            'subjects'
        ));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, int $id)
    {
        $request->validate([
            'assignment_score' => 'nullable|numeric|min:0|max:100',
            'mid_exam_score' => 'nullable|numeric|min:0|max:100',
            'final_exam_score' => 'nullable|numeric|min:0|max:100',
        ]);

        $grade = Grade::where('archived', 0)
            ->findOrFail($id);

        $grade->update([
            'assignment_score' => $request->assignment_score ?? 0,
            'mid_exam_score' => $request->mid_exam_score ?? 0,
            'final_exam_score' => $request->final_exam_score ?? 0,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status' => true,
                'message' => 'Nilai berhasil diupdate'
            ]);
        }

        return redirect()
            ->route('grades.index')
            ->with('success', 'Nilai berhasil diupdate');
    }

    // =========================
    // DELETE => ARCHIVE
    // =========================
    public function destroy(Request $request, int $id)
    {
        $grade = Grade::where('archived', 0)
            ->findOrFail($id);

        $grade->update([
            'archived' => 1
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dipindahkan ke arsip'
            ]);
        }

        return redirect()
            ->route('grades.index')
            ->with('success', 'Data berhasil dipindahkan ke arsip');
    }
}