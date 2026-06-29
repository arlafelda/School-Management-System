<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = ActivityLog::with('user')->latest();

        // ─── Filter: pencarian teks ───────────────────────────────────────
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('user_name', 'like', "%{$search}%")
                  ->orWhere('target_label', 'like', "%{$search}%");
            });
        }

        // ─── Filter: modul ────────────────────────────────────────────────
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // ─── Filter: aksi ─────────────────────────────────────────────────
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // ─── Filter: user_role ────────────────────────────────────────────
        if ($request->filled('role')) {
            $query->where('user_role', $request->role);
        }

        // ─── Filter: user_id (hanya super_admin) ──────────────────────────
        if ($request->filled('user_id') && $user->role === 'super_admin') {
            $query->where('user_id', $request->user_id);
        }

        // ─── Filter: tanggal ──────────────────────────────────────────────
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // ─── Jika admin (bukan super_admin) hanya lihat log yg bukan super_admin ─
        if ($user->role === 'admin') {
            $query->where('user_role', '!=', 'super_admin');
        }

        $logs = $query->paginate(25)->withQueryString();

        // ─── Daftar untuk filter dropdown ─────────────────────────────────
        $modules = ActivityLog::select('module')->distinct()->orderBy('module')->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');
        $users   = $user->role === 'super_admin'
            ? User::withTrashed()->orderBy('name')->get(['id', 'name', 'role'])
            : collect();

        // ─── Stats ringkasan ──────────────────────────────────────────────
        $stats = [
            'total'        => ActivityLog::count(),
            'today'        => ActivityLog::whereDate('created_at', today())->count(),
            'this_week'    => ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'this_month'   => ActivityLog::whereMonth('created_at', now()->month)->count(),
        ];

        return view('activity-log.index', compact('logs', 'modules', 'actions', 'users', 'stats'));
    }

    public function show(ActivityLog $activityLog)
    {
        $user = Auth::user();

        // Admin tidak bisa melihat detail log super_admin
        if ($user->role === 'admin' && $activityLog->user_role === 'super_admin') {
            abort(403);
        }

        return view('activity-log.show', compact('activityLog'));
    }
}
