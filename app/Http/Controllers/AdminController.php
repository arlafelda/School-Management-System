<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // INDEX
    public function index()
    {
        $admins = User::where('role', 'admin')->get();

        return view('admin.admin-index', compact('admins'));
    }

    // CREATE
    public function create()
    {
        return view('admin.admin-add');
    }

    // STORE
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|unique:tbl_users,email',
                'password' => 'required|min:6',
            ]);

            $admin = User::create([
                'name'            => $request->name,
                'email'           => $request->email,
                'password'        => Hash::make($request->password),
                'role'            => 'admin',
                'creation_time'   => now(),
                'create_id'       => Auth::id() ?? 0,
                'update_time'     => now(),
                'update_id'       => Auth::id() ?? 0,
            ]);

            // AUTO SLUG UNIK
            $admin->slug = Str::slug($request->name) . '-' . $admin->id;
            $admin->save();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dibuat',
                'data'    => $admin
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // SHOW / EDIT
    // SHOW (detail/view only)
    public function show(string $slug)
    {
        $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

        return view('admin.admin-show', compact('admin'));
    }

    // EDIT (form edit)
    public function edit(string $slug)
    {
        $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

        return view('admin.admin-edit', compact('admin'));
    }
    // UPDATE
    public function update(Request $request, string $slug)
    {
        try {
            $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_users,email,' . $admin->id,
            ]);

            $newSlug = Str::slug($request->name) . '-' . $admin->id;

            $admin->update([
                'name'        => $request->name,
                'email'       => $request->email,
                'slug'        => $newSlug,
                'update_time' => now(),
                'update_id'   => Auth::id() ?? 0,
            ]);

            if ($request->filled('password')) {
                $admin->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diupdate',
                'slug'    => $newSlug
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal update admin',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // SOFT DELETE (arsip)
    public function destroy(string $slug)
    {
        try {
            $admin = User::where('slug', $slug)->where('role', 'admin')->firstOrFail();

            $admin->update([
                'update_time' => now(),
                'update_id'   => Auth::id() ?? 0
            ]);

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diarsipkan'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus admin',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    // ARCHIVE LIST
    public function archived()
    {
        $admins = User::onlyTrashed()->where('role', 'admin')->get();

        return view('admin.admin-archived', compact('admins'));
    }

    // RESTORE
    public function restore(string $slug)
    {
        try {
            $admin = User::onlyTrashed()
                ->where('slug', $slug)
                ->where('role', 'admin')
                ->firstOrFail();

            $admin->restore();

            return redirect()
                ->route('admin.archived')
                ->with('success', 'Admin berhasil direstore');
        } catch (\Throwable $e) {
            return redirect()
                ->route('admin.archived')
                ->with('error', $e->getMessage());
        }
    }

    // FORCE DELETE (PERMANENT)
    public function forceDelete(string $slug)
    {
        try {
            $admin = User::onlyTrashed()
                ->where('slug', $slug)
                ->where('role', 'admin')
                ->firstOrFail();

            $admin->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dihapus permanen'
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus permanen',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
