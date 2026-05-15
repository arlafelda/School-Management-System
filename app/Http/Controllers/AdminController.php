<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // =========================
    // INDEX (admin aktif)
    // =========================
    public function index()
    {
        $admins = User::where('role', 'admin')
            ->where('archived', 0)
            ->get();

        return view('admin.admin-index', compact('admins'));
    }

    // =========================
    // CREATE
    // =========================
    public function create()
    {
        return view('admin.admin-add');
    }

    // =========================
    // STORE
    // =========================
    public function store(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_users,email',
                'password' => 'required|min:6',
            ]);

            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'admin',
                'archived' => 0,
                'creation_time' => now(),
                'create_id' => Auth::id(),
            ]);

            $admin->slug = Str::slug($request->name) . '-' . $admin->id;
            $admin->save();

            return response()->json([
                'success' => true,
                'data' => $admin,
                'message' => 'Admin berhasil dibuat'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // SHOW
    // =========================
    public function show(string $slug)
    {
        $admin = User::where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        return view('admin.admin-show', compact('admin'));
    }

    // =========================
    // EDIT
    // =========================
    public function edit(string $slug)
    {
        $admin = User::where('slug', $slug)
            ->where('archived', 0)
            ->firstOrFail();

        return view('admin.admin-edit', compact('admin'));
    }

    // =========================
    // UPDATE
    // =========================
    public function update(Request $request, string $slug)
    {
        try {

            $admin = User::where('slug', $slug)
                ->where('archived', 0)
                ->firstOrFail();

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:tbl_users,email,' . $admin->id,
            ]);

            $newSlug = Str::slug($request->name) . '-' . uniqid();

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'slug' => $newSlug,
                'update_time' => now(),
                'update_id' => Auth::id(),
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $admin->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil diupdate',
                'slug' => $newSlug
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // ARCHIVE (DELETE)
    // =========================
    public function destroy(string $slug)
    {
        try {

            $admin = User::where('slug', $slug)
                ->where('archived', 0)
                ->firstOrFail();

            $admin->update([
                'archived' => 1,
                'update_time' => now(),
                'update_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dipindahkan ke arsip'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // =========================
    // HALAMAN ARSIP
    // =========================
    public function archived()
    {
        $admins = User::where('role', 'admin')
            ->where('archived', 1)
            ->get();

        return view('admin.admin-archived', compact('admins'));
    }

    // =========================
    // RESTORE
    // =========================
    public function restore(string $slug)
    {
        try {

            $admin = User::where('slug', $slug)
                ->where('archived', 1)
                ->firstOrFail();

            $admin->update([
                'archived' => 0,
                'update_time' => now(),
                'update_id' => Auth::id()
            ]);

            return redirect()
                ->route('admin.archived')
                ->with('success', 'Admin berhasil direstore');

        } catch (\Throwable $e) {

            return redirect()
                ->route('admin.archived')
                ->with('error', $e->getMessage());
        }
    }
}