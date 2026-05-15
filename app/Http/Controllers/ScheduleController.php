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

        $classes = ClassModel::where('archived', 0)->get();

        $schedules = Schedule::with(['teacher', 'class'])
            ->where('archived', 0)

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

            // SEARCH
            ->when($request->search, function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('day', 'like', "%{$search}%")
                        ->orWhere('start_time', 'like', "%{$search}%")
                        ->orWhere('end_time', 'like', "%{$search}%")

                        ->orWhereHas('class', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%")
                                ->orWhere('major', 'like', "%{$search}%");
                        })

                        ->orWhereHas('teacher', function ($q3) use ($search) {
                            $q3->where('name', 'like', "%{$search}%")
                                ->orWhere('subject', 'like', "%{$search}%");
                        });
                });
            })

            ->orderBy('day', 'asc')
            ->get();

        return view('schedule.schedule-index', compact(
            'schedules',
            'classes'
        ));
    }

    public function archived()
    {
        $schedules = Schedule::with(['teacher', 'class'])
            ->where('archived', 1)
            ->latest()
            ->get();

        return view('schedule.schedule-archived', compact('schedules'));
    }

    public function create()
    {
        $teachers = Teacher::where('archived', 0)->get();
        $classes = ClassModel::where('archived', 0)->get();

        return view('schedule.schedule-add', compact('teachers', 'classes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'teacher_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $schedule = Schedule::create([
            'class_id' => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'archived' => 0,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan',
                'data' => $schedule
            ]);
        }

        return redirect()
            ->route('schedule.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit(int $id)
    {
        $schedule = Schedule::where('archived', 0)
            ->findOrFail($id);

        $classes = ClassModel::where('archived', 0)->get();
        $teachers = Teacher::where('archived', 0)->get();

        return view('schedule.schedule-edit', compact(
            'schedule',
            'classes',
            'teachers'
        ));
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'class_id' => 'required',
            'teacher_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $schedule = Schedule::where('archived', 0)
            ->findOrFail($id);

        $schedule->update([
            'class_id' => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui',
                'data' => $schedule
            ]);
        }

        return redirect()
            ->route('schedule.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function show(int $id)
    {
        $schedule = Schedule::with(['class', 'teacher'])
            ->where('archived', 0)
            ->findOrFail($id);

        return view('schedule.schedule-show', compact('schedule'));
    }

    public function destroy(int $id, Request $request)
    {
        $schedule = Schedule::where('archived', 0)
            ->findOrFail($id);

        $schedule->update([
            'archived' => 1
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dipindahkan ke arsip'
            ]);
        }

        return redirect()
            ->route('schedule.index')
            ->with('success', 'Jadwal berhasil dipindahkan ke arsip');
    }

    public function restore(int $id, Request $request)
    {
        $schedule = Schedule::where('archived', 1)
            ->findOrFail($id);

        $schedule->update([
            'archived' => 0
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil direstore'
            ]);
        }

        return redirect()
            ->route('schedule.archived')
            ->with('success', 'Jadwal berhasil direstore');
    }
}
