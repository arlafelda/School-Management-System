<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            $user = $request->user();

            // 🔥 Blokir akun non-aktif
            if ($user->archived == 1) {
                Auth::logout();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun tidak aktif',
                    ], 403);
                }

                return back()->withErrors(['email' => 'Akun tidak aktif']);
            }

            // 📝 Catat aktivitas login
            ActivityLogService::login("Login ke sistem sebagai {$user->role}: {$user->name}");

            // 🔥 Redirect berdasarkan role
            $redirect = match ($user->role) {
                'super_admin' => '/dashboard/superadmin',
                'admin'       => '/dashboard/admin',
                'teacher'     => '/dashboard/teacher',
                default       => '/dashboard/student',
            };

            // ✅ Response AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success'  => true,
                    'message'  => 'Login berhasil',
                    'redirect' => $redirect,
                ]);
            }

            // ✅ Response non-AJAX
            return redirect($redirect);

        } catch (\Illuminate\Validation\ValidationException $e) {

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah',
                ], 422);
            }

            return back()->withErrors(['email' => 'Email atau password salah']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // 📝 Catat aktivitas logout sebelum sesi dihapus
        $user = Auth::user();
        if ($user) {
            ActivityLogService::logout("Logout dari sistem: {$user->name} ({$user->role})");
        }

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}