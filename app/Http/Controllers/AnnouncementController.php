<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    // =============================================
    // INDEX — daftar semua pengumuman (admin/super_admin)
    // =============================================
    public function index(Request $request): View
    {
        $query = Announcement::withTrashed()
            ->with('author')
            ->latest();

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('target_role')) {
            $query->where('target_role', $request->target_role);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $announcements = $query->paginate(10)->withQueryString();

        return view('Announcement.announcement-index', compact('announcements'));
    }

    // =============================================
    // STORE — simpan pengumuman baru
    // =============================================
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'priority'    => 'required|in:normal,penting,mendesak',
            'target_role' => 'required|in:all,student,teacher,admin',
            'expired_at'  => 'nullable|date|after:today',
            'is_active'   => 'nullable|boolean',
        ]);

        $announcement = Announcement::create([
            ...$validated,
            'created_by'   => Auth::id(),
            'published_at' => now(),
            'is_active'    => $request->boolean('is_active', true),
        ]);

        ActivityLogService::create(
            'Announcement',
            "Menambahkan pengumuman baru \"{$announcement->title}\".",
            $announcement->title,
            $announcement->toArray()
        );

        return redirect()->route('announcement.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    // =============================================
    // SHOW — detail pengumuman
    // =============================================
    public function show(Announcement $announcement): View
    {
        $announcement->load('author');

        return view('Announcement.announcement-show', compact('announcement'));
    }

    // =============================================
    // UPDATE — perbarui pengumuman
    // =============================================
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'priority'    => 'required|in:normal,penting,mendesak',
            'target_role' => 'required|in:all,student,teacher,admin',
            'expired_at'  => 'nullable|date|after:today',
            'is_active'   => 'nullable|boolean',
        ]);

        $oldData = $announcement->toArray();

        $announcement->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        ActivityLogService::update(
            'Announcement',
            "Memperbarui pengumuman \"{$announcement->title}\".",
            $announcement->title,
            $oldData,
            $announcement->toArray()
        );

        return redirect()->route('announcement.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    // =============================================
    // DESTROY — soft delete pengumuman
    // =============================================
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $oldData = $announcement->toArray();
        $title   = $announcement->title;

        $announcement->delete();

        ActivityLogService::delete(
            'Announcement',
            "Menghapus pengumuman \"{$title}\".",
            $title,
            $oldData
        );

        return redirect()->route('announcement.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    // =============================================
    // RESTORE — pulihkan dari soft delete
    // =============================================
    public function restore(int $id): RedirectResponse
    {
        $announcement = Announcement::onlyTrashed()->findOrFail($id);
        $announcement->restore();

        ActivityLogService::restore(
            'Announcement',
            "Memulihkan pengumuman \"{$announcement->title}\".",
            $announcement->title
        );

        return redirect()->route('announcement.index')
            ->with('success', 'Pengumuman berhasil dipulihkan.');
    }

    // =============================================
    // FORCE DELETE — hapus permanen
    // =============================================
    public function forceDelete(int $id): RedirectResponse
    {
        $announcement = Announcement::onlyTrashed()->findOrFail($id);
        $oldData      = $announcement->toArray();
        $title        = $announcement->title;

        $announcement->forceDelete();

        ActivityLogService::forceDelete(
            'Announcement',
            "Menghapus permanen pengumuman \"{$title}\".",
            $title,
            $oldData
        );

        return redirect()->route('announcement.index')
            ->with('success', 'Pengumuman berhasil dihapus permanen.');
    }

    // =============================================
    // BOARD — papan pengumuman untuk semua role
    // Accessible: super_admin, admin, teacher, student
    // =============================================
    public function board(): View
    {
        $role = Auth::user()->role;

        $announcements = Announcement::active()
            ->forRole($role)
            ->with('author')
            ->orderByRaw("FIELD(priority, 'mendesak', 'penting', 'normal')")
            ->latest()
            ->get();

        return view('Announcement.announcement-board', compact('announcements'));
    }
}