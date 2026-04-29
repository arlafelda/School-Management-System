<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    // ✅ TAMPILKAN DATA ADMIN
    public function index()
    {
        $admins = User::where('role', 'admin')->get();

        return view('admin.admin-index', compact('admins'));
    }

    // ✅ FORM TAMBAH
    public function create()
    {
        return view('admin.admin-add');
    }

    // ✅ SIMPAN DATA
    public function store(Request $request)
    {
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
            'archived' => $request->archived ?? 0,
            'creation_time' => now(),
            'create_id' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $admin,
            'message' => 'Admin berhasil dibuat'
        ]);
    }
    public function edit($id)
    {
        $admin = User::findOrFail($id);
        return view('admin.admin-edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = User::findOrFail($id);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil diupdate'
        ]);
    }

    public function show($id)
    {
        $admin = User::findOrFail($id);

        return view('admin.admin-show', compact('admin'));
    }

    public function destroy($id)
    {
        $admin = User::findOrFail($id);

        $admin->update([
            'archived' => 1,
            'update_time' => now(),
            'update_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil dihapus'
        ]);
    }
}
