<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    // =====================
    // INDEX
    // =====================
    public function index(Request $request)
    {
        $user = Auth::user();

        $classes = ClassModel::where('archived', 0)->get();

        $query = Grade::with([
            'student.class',
            'schedule.teacher',
            'schedule.subject',
            'schedule.classModel',
            'subject'
        ])->where('archived', 0);

        if ($user->role === 'student') {

            $student = $user->student;

            if (!$student) {
                abort(403, 'Data student tidak ditemukan');
            }

            $query->where('student_id', $student->id);
        } elseif ($user->role === 'teacher') {

            $teacher = Teacher::where(
                'user_id',
                $user->id
            )->first();

            if ($teacher) {
                $scheduleIds = Schedule::where(
                    'teacher_id',
                    $teacher->id
                )
                    ->where('archived', 0)
                    ->pluck('id');

                $query->whereIn(
                    'schedule_id',
                    $scheduleIds
                );
            }
        }

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

        return view('grade.grade-index', compact(
            'data',
            'classes'
        ));
    }


    // =====================
    // CREATE
    // =====================
    public function create(Request $request)
    {
        $user = Auth::user();

        $classes = ClassModel::where('archived', 0)->get();
        $teachers = Teacher::where('archived', 0)->get();

        $schedules = collect();
        $students = collect();

        if ($user->role === 'teacher') {

            $teacher = Teacher::where(
                'user_id',
                $user->id
            )->first();

            if (!$teacher) {
                return redirect()
                    ->route('grades.index')
                    ->with('error', 'Teacher tidak ditemukan');
            }

            $schedules = Schedule::with([
                'subject',
                'classModel',
                'teacher'
            ])
                ->where('teacher_id', $teacher->id)
                ->where('archived', 0)
                ->get();
        } else {
            $schedules = Schedule::with([
                'subject',
                'classModel',
                'teacher'
            ])
                ->where('archived', 0)
                ->get();
        }

        if ($request->schedule_id) {
            $schedule = Schedule::find($request->schedule_id);

            if ($schedule) {
                $students = Student::with('class')
                    ->where('class_id', $schedule->class_id)
                    ->where('archived', 0)
                    ->get();
            }
        }

        return view('grade.grade-add', compact(
            'classes',
            'teachers',
            'schedules',
            'students'
        ));
    }


    // =====================
    // STORE
    // =====================
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id'       => 'required|exists:tbl_schedules,id',
            'subject_id'        => 'required|exists:tbl_subjects,id',
            'student_id'        => 'required|array',
            'assignment_score'  => 'required|array',
            'mid_exam_score'    => 'required|array',
            'final_exam_score'  => 'required|array',
        ]);

        $saved = 0;
        $skipped = 0;

        foreach ($request->student_id as $i => $studentId) {

            $exists = Grade::where('student_id', $studentId)
                ->where('schedule_id', $request->schedule_id)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Grade::create([
                'student_id'       => $studentId,
                'schedule_id'      => $request->schedule_id,
                'subject_id'       => $request->subject_id,
                'assignment_score' => $request->assignment_score[$i] ?? 0,
                'mid_exam_score'   => $request->mid_exam_score[$i] ?? 0,
                'final_exam_score' => $request->final_exam_score[$i] ?? 0,
                'archived'         => 0
            ]);

            $saved++;
        }

        /*
    =====================
    RESPONSE
    =====================
    */
        if ($saved == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Nilai untuk jadwal ini sudah pernah diinput, tidak bisa input ulang'
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => "Berhasil menyimpan {$saved} data nilai"
        ]);
    }

    public function edit(int $id)
    {
        $user = Auth::user();

        $grade = Grade::with([
            'student.class',
            'schedule.teacher',
            'schedule.subject',
            'schedule.classModel',
            'subject'
        ])->findOrFail($id);

        if ($grade->archived == 1) {
            abort(404);
        }

        $subjects = collect();
        $schedules = collect();

        /*
    =====================
    TEACHER
    =====================
    */
        if ($user->role === 'teacher') {

            $teacher = Teacher::where(
                'user_id',
                $user->id
            )->first();

            if ($teacher) {

                $schedules = Schedule::with([
                    'subject',
                    'classModel',
                    'teacher'
                ])
                    ->where('teacher_id', $teacher->id)
                    ->where('archived', 0)
                    ->get();

                $subjects = Subject::whereIn(
                    'id',
                    $schedules->pluck('subject_id')
                )->get();
            }
        }

        /*
    =====================
    ADMIN / SUPER ADMIN
    =====================
    */ else {

            $subjects = Subject::where('archived', 0)->get();

            $schedules = Schedule::with([
                'subject',
                'classModel',
                'teacher'
            ])
                ->where('archived', 0)
                ->get();
        }

        return view('grade.grade-edit', compact(
            'grade',
            'subjects',
            'schedules'
        ));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, int $id)
    {
        $request->validate([
            'assignment_score' => 'required|numeric|min:0|max:100',
            'mid_exam_score'   => 'required|numeric|min:0|max:100',
            'final_exam_score' => 'required|numeric|min:0|max:100',
        ]);

        $grade = Grade::where(
            'archived',
            0
        )->findOrFail($id);

        $grade->update([
            'assignment_score' => $request->assignment_score,
            'mid_exam_score'   => $request->mid_exam_score,
            'final_exam_score' => $request->final_exam_score,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'status'  => true,
                'message' => 'Nilai berhasil diupdate'
            ]);
        }

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Nilai berhasil diupdate'
            );
    }

    // =====================
    // SHOW
    // =====================
    public function show(int $id)
    {
        $grade = Grade::with([
            'student.class',
            'schedule.teacher',
            'schedule.subject',
            'schedule.classModel',
            'subject'
        ])->findOrFail($id);

        if ($grade->archived == 1) {
            abort(404);
        }

        return view('grade.grade-show', compact('grade'));
    }


    // =====================
    // ARCHIVED
    // =====================
    public function archived()
    {
        $grades = Grade::with([
            'student.class',
            'schedule.teacher',
            'schedule.subject',
            'schedule.classModel',
            'subject'
        ])
            ->where('archived', 1)
            ->latest()
            ->get();

        return view(
            'grade.grade-archived',
            compact('grades')
        );
    }


    // =====================
    // RESTORE
    // =====================
    public function restore(int $id)
    {
        $grade = Grade::where(
            'archived',
            1
        )->findOrFail($id);

        $grade->update([
            'archived' => 0
        ]);

        return redirect()
            ->route('grades.archived')
            ->with(
                'success',
                'Data nilai berhasil direstore'
            );
    }


    // =====================
    // ARCHIVE
    // =====================
    public function destroy(int $id)
    {
        $grade = Grade::where(
            'archived',
            0
        )->findOrFail($id);

        $grade->update([
            'archived' => 1
        ]);

        return redirect()
            ->route('grades.index')
            ->with(
                'success',
                'Data berhasil dipindahkan ke arsip'
            );
    }


    // =====================
    // DELETE
    // =====================
    public function delete(int $id)
    {
        $grade = Grade::where(
            'archived',
            1
        )->findOrFail($id);

        $grade->delete();

        return redirect()
            ->route('grades.archived')
            ->with(
                'success',
                'Data nilai berhasil dihapus permanen'
            );
    }
}
