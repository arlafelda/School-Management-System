<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Teacher;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $classes = ClassModel::all();

        $schedules = Schedule::with(['teacher', 'class'])
            ->when($request->class_id, function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })
            ->orderBy('day', 'asc') // ✅ FIX: tidak pakai 'date'
            ->get();

        return view('schedule.schedule-index', compact('schedules', 'classes'));
    }

    public function create()
    {
        $teachers = Teacher::all();
        $classes = ClassModel::all();

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
        ]);

        // 🔥 AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil ditambahkan',
                'data' => $schedule
            ]);
        }

        return redirect()->route('schedule.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $schedule = Schedule::findOrFail($id);
        $classes = ClassModel::all();
        $teachers = Teacher::all();

        return view('schedule.schedule-edit', compact('schedule', 'classes', 'teachers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required',
            'teacher_id' => 'required',
            'day' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'class_id' => $request->class_id,
            'teacher_id' => $request->teacher_id,
            'day' => $request->day,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        // 🔥 AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil diperbarui',
                'data' => $schedule
            ]);
        }

        return redirect()->route('schedule.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function show($id)
    {
        $schedule = Schedule::with(['class', 'teacher'])->findOrFail($id);

        return view('schedule.schedule-show', compact('schedule'));
    }

    public function destroy($id, Request $request)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        // 🔥 AJAX RESPONSE
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Jadwal berhasil dihapus'
            ]);
        }

        return redirect()->route('schedule.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }
}
