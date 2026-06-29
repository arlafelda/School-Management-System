<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\Subject;
use App\Services\ActivityLogService;
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

        $classes = ClassModel::all();

        $query = Grade::with([
            'student.class',
            'schedule.teacher',
            'schedule.subject',
            'schedule.classModel',
            'subject'
        ]);

        if ($user->role === 'student') {

            $student = $user->student;

            if (!$student) {
                abort(403, 'Data student tidak ditemukan');
            }

            $query->where('student_id', $student->id);

        } elseif ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {
                $scheduleIds = Schedule::where('teacher_id', $teacher->id)->pluck('id');
                $query->whereIn('schedule_id', $scheduleIds);
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

        return view('grade.grade-index', compact('data', 'classes'));
    }


    // =====================
    // CREATE
    // =====================
    public function create(Request $request)
    {
        $user = Auth::user();

        $classes  = ClassModel::all();
        $teachers = Teacher::all();

        $schedules = collect();
        $students  = collect();

        if ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            if (!$teacher) {
                return redirect()
                    ->route('grades.index')
                    ->with('error', 'Teacher tidak ditemukan');
            }

            $schedules = Schedule::with(['subject', 'classModel', 'teacher'])
                ->where('teacher_id', $teacher->id)
                ->get();

        } else {

            $schedules = Schedule::with(['subject', 'classModel', 'teacher'])->get();
        }

        if ($request->schedule_id) {
            $schedule = Schedule::find($request->schedule_id);

            if ($schedule) {
                $students = Student::with('class')
                    ->where('class_id', $schedule->class_id)
                    ->get();
            }
        }

        return view('grade.grade-add', compact('classes', 'teachers', 'schedules', 'students'));
    }


    // =====================
    // STORE
    // =====================
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id'      => 'required|exists:tbl_schedules,id',
            'subject_id'       => 'required|exists:tbl_subjects,id',
            'student_id'       => 'required|array',
            'assignment_score' => 'required|array',
            'mid_exam_score'   => 'required|array',
            'final_exam_score' => 'required|array',
        ]);

        $saved   = 0;
        $skipped = 0;

        $schedule = Schedule::with(['subject', 'classModel'])->find($request->schedule_id);

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
            ]);

            $saved++;
        }

        if ($saved == 0) {
            return response()->json([
                'status'  => false,
                'message' => 'Nilai untuk jadwal ini sudah pernah diinput, tidak bisa input ulang'
            ], 422);
        }

        // 📝 Catat aktivitas
        $subjectName = $schedule?->subject?->name ?? '-';
        $className   = $schedule?->classModel?->name ?? '-';

        ActivityLogService::create(
            'Grade',
            "Menyimpan nilai {$saved} siswa — Mapel: {$subjectName}, Kelas: {$className}" .
            ($skipped > 0 ? ", Dilewati: {$skipped} (sudah ada)" : ''),
            "{$subjectName} / {$className}",
        );

        return response()->json([
            'status'  => true,
            'message' => "Berhasil menyimpan {$saved} data nilai",
        ]);
    }


    // =====================
    // EDIT
    // =====================
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

        $subjects  = collect();
        $schedules = collect();

        if ($user->role === 'teacher') {

            $teacher = Teacher::where('user_id', $user->id)->first();

            if ($teacher) {
                $schedules = Schedule::with(['subject', 'classModel', 'teacher'])
                    ->where('teacher_id', $teacher->id)
                    ->get();

                $subjects = Subject::whereIn('id', $schedules->pluck('subject_id'))->get();
            }

        } else {

            $subjects  = Subject::all();
            $schedules = Schedule::with(['subject', 'classModel', 'teacher'])->get();
        }

        return view('grade.grade-edit', compact('grade', 'subjects', 'schedules'));
    }


    // =====================
    // UPDATE
    // =====================
    public function update(Request $request, int $id)
    {
        $request->validate([
            'assignment_score' => 'required|numeric|min:0|max:100',
            'mid_exam_score'   => 'required|numeric|min:0|max:100',
            'final_exam_score' => 'required|numeric|min:0|max:100',
        ]);

        $grade = Grade::with([
            'student',
            'schedule.subject',
            'schedule.classModel',
        ])->findOrFail($id);

        // Simpan data lama sebelum diupdate
        $oldData = $grade->only(['assignment_score', 'mid_exam_score', 'final_exam_score']);

        $grade->update([
            'assignment_score' => $request->assignment_score,
            'mid_exam_score'   => $request->mid_exam_score,
            'final_exam_score' => $request->final_exam_score,
        ]);

        // 📝 Catat aktivitas
        $studentName = $grade->student?->name ?? '-';
        $subjectName = $grade->schedule?->subject?->name ?? '-';
        $className   = $grade->schedule?->classModel?->name ?? '-';

        ActivityLogService::update(
            'Grade',
            "Mengupdate nilai siswa: {$studentName} — Mapel: {$subjectName}, Kelas: {$className}",
            $studentName,
            $oldData,
            $grade->only(['assignment_score', 'mid_exam_score', 'final_exam_score'])
        );

        if ($request->ajax()) {
            return response()->json([
                'status'  => true,
                'message' => 'Nilai berhasil diupdate',
            ]);
        }

        return redirect()
            ->route('grades.index')
            ->with('success', 'Nilai berhasil diupdate');
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

        return view('grade.grade-show', compact('grade'));
    }


    // =====================
    // DELETE (PERMANENT — data nilai tidak di-arsip)
    // =====================
    public function destroy(int $id)
    {
        $grade = Grade::with([
            'student',
            'schedule.subject',
            'schedule.classModel',
        ])->findOrFail($id);

        // 📝 Catat aktivitas sebelum dihapus permanen
        $studentName = $grade->student?->name ?? '-';
        $subjectName = $grade->schedule?->subject?->name ?? '-';
        $className   = $grade->schedule?->classModel?->name ?? '-';

        ActivityLogService::delete(
            'Grade',
            "Menghapus nilai siswa: {$studentName} — Mapel: {$subjectName}, Kelas: {$className}",
            $studentName,
            $grade->only(['assignment_score', 'mid_exam_score', 'final_exam_score', 'schedule_id', 'subject_id'])
        );

        $grade->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Data nilai berhasil dihapus',
            ]);
        }

        return redirect()
            ->route('grades.index')
            ->with('success', 'Data nilai berhasil dihapus');
    }
}