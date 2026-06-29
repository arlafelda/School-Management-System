<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display a listing of admins.
     */
    public function index()
    {
        $admins = User::where('role', 'admin')->get();

        return view('admin.admin-index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        return view('admin.admin-add');
    }

    /**
     * Store a newly created admin.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:tbl_users,email',
                'password' => 'required|min:6',
            ]);

            $admin = User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => Hash::make($request->password),
                'role'          => 'admin',
                'creation_time' => now(),
                'create_id'     => Auth::id() ?? 0,
                'update_time'   => now(),
                'update_id'     => Auth::id() ?? 0,
            ]);

            // Auto slug unik
            $admin->slug = Str::slug($request->name) . '-' . $admin->id;
            $admin->save();

            // 📝 Catat aktivitas
            ActivityLogService::create(
                'Admin',
                "Menambahkan admin baru: {$admin->name} ({$admin->email})",
                $admin->name,
                $admin->toArray()
            );

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dibuat',
                'data'    => $admin,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified admin (view only).
     */
    public function show(string $slug)
    {
        $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

        return view('admin.admin-show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(string $slug)
    {
        $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

        return view('admin.admin-edit', compact('admin'));
    }

    /**
     * Update the specified admin.
     */
    public function update(Request $request, string $slug)
    {
        try {
            $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_users,email,' . $admin->id,
            ]);

            // Simpan data lama sebelum diupdate
            $oldData = $admin->only(['name', 'email', 'slug']);

            $newSlug = Str::slug($request->name) . '-' . $admin->id;

            $admin->update([
                'name'        => $request->name,
                'email'       => $request->email,
                'slug'        => $newSlug,
                'update_time' => now(),
                'update_id'   => Auth::id() ?? 0,
            ]);

            if ($request->filled('password')) {
                $admin->update(['password' => Hash::make($request->password)]);
            }

            // 📝 Catat aktivitas
            ActivityLogService::update(
                'Admin',
                "Mengupdate data admin: {$admin->name} ({$admin->email})",
                $admin->name,
                $oldData,
                $admin->only(['name', 'email', 'slug'])
            );

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diupdate',
                'slug'    => $newSlug,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update admin',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soft delete (arsip) the specified admin.
     */
    public function destroy(string $slug)
    {
        try {
            $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

            // 📝 Catat aktivitas sebelum dihapus
            ActivityLogService::delete(
                'Admin',
                "Mengarsipkan admin: {$admin->name} ({$admin->email})",
                $admin->name,
                $admin->only(['name', 'email', 'slug'])
            );

            $admin->update([
                'update_time' => now(),
                'update_id'   => Auth::id() ?? 0,
            ]);

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diarsipkan',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus admin',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display a listing of archived admins.
     */
    public function archived()
    {
        $admins = User::onlyTrashed()->where('role', 'admin')->get();

        return view('admin.admin-archived', compact('admins'));
    }

    /**
     * Restore the specified archived admin.
     */
    public function restore(string $slug)
    {
        try {
            $admin = User::onlyTrashed()
                ->where('slug', $slug)
                ->where('role', 'admin')
                ->firstOrFail();

            $admin->restore();

            // 📝 Catat aktivitas
            ActivityLogService::restore(
                'Admin',
                "Merestore admin: {$admin->name} ({$admin->email})",
                $admin->name
            );

            return redirect()
                ->route('admin.archived')
                ->with('success', 'Admin berhasil direstore');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.archived')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Permanently delete the specified admin.
     */
    public function forceDelete(string $slug)
    {
        try {
            $admin = User::onlyTrashed()
                ->where('slug', $slug)
                ->where('role', 'admin')
                ->firstOrFail();

            // 📝 Catat aktivitas sebelum dihapus permanen
            ActivityLogService::forceDelete(
                'Admin',
                "Menghapus permanen admin: {$admin->name} ({$admin->email})",
                $admin->name,
                $admin->only(['name', 'email', 'slug'])
            );

            $admin->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dihapus permanen',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}