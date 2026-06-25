<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\ClassModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $classes = ClassModel::all();

        $schedules = Schedule::with(['teacher', 'classModel', 'subject'])

            ->when($user->role === 'student', function ($query) use ($user) {
                $student = Student::where('user_id', $user->id)->first();

                if ($student) {
                    $query->where('class_id', $student->class_id);
                } else {
                    $query->whereRaw('1 = 0');
                }
            })

            ->when($user->role === 'teacher', function ($query) use ($user) {
                $teacher = Teacher::where('user_id', $user->id)->first();

                if ($teacher) {
                    $query->where('teacher_id', $teacher->id);
                }
            })

            ->when($request->class_id, function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })

            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('day', 'like', "%{$search}%")
                      ->orWhere('start_time', 'like', "%{$search}%")
                      ->orWhere('end_time', 'like', "%{$search}%")

                      ->orWhereHas('classModel', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhere('major', 'like', "%{$search}%");
                      })

                      ->orWhereHas('teacher', function ($q3) use ($search) {
                          $q3->where('name', 'like', "%{$search}%");
                      })

                      ->orWhereHas('subject', function ($q4) use ($search) {
                          $q4->where('name', 'like', "%{$search}%");
                      });
                });
            })

            ->orderBy('day', 'asc')
            ->get();

        return view('schedule.schedule-index', compact('schedules', 'classes'));
    }

    public function archived()
    {
        $schedules = Schedule::onlyTrashed()
            ->with(['teacher', 'classModel', 'subject'])
            ->latest()
            ->get();

        return view('schedule.schedule-archived', compact('schedules'));
    }

    public function create()
    {
        $teachers = Teacher::with('subjects')->get();
        $classes  = ClassModel::all();

        return view('schedule.schedule-add', compact('teachers', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id'   => 'required',
            'teacher_id' => 'required',
            'subject_id' => 'required',
            'day'        => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        Schedule::create([
            'class_id'   => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil ditambahkan'
        ]);
    }

    public function edit(int $id)
    {
        $schedule = Schedule::findOrFail($id);
        $classes  = ClassModel::all();
        $teachers = Teacher::with('subjects')->get();

        return view('schedule.schedule-edit', compact('schedule', 'classes', 'teachers'));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'class_id'   => 'required',
            'teacher_id' => 'required',
            'subject_id' => 'required',
            'day'        => 'required',
            'start_time' => 'required',
            'end_time'   => 'required',
        ]);

        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'class_id'   => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'day'        => $request->day,
            'start_time' => $request->start_time,
            'end_time'   => $request->end_time,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil diperbarui'
        ]);
    }

    public function show(int $id)
    {
        $schedule = Schedule::with(['classModel', 'teacher', 'subject'])->findOrFail($id);

        return view('schedule.schedule-show', compact('schedule'));
    }

    // SOFT DELETE (arsip)
    public function destroy(int $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dipindahkan ke arsip'
        ]);
    }

    // RESTORE
    public function restore(int $id)
    {
        $schedule = Schedule::onlyTrashed()->findOrFail($id);
        $schedule->restore();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil direstore'
        ]);
    }

    // FORCE DELETE (PERMANENT)
    public function delete(int $id)
    {
        $schedule = Schedule::onlyTrashed()->findOrFail($id);
        $schedule->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Jadwal berhasil dihapus permanen'
        ]);
    }
}
