<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
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

            // 🔥 akun non aktif
            if ($user->archived == 1) {
                Auth::logout();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun tidak aktif'
                    ], 403);
                }

                return back()->withErrors(['email' => 'Akun tidak aktif']);
            }

            // 🔥 redirect role
            if ($user->role == 'super_admin') {
                $redirect = '/dashboard/superadmin';
            } elseif ($user->role == 'admin') {
                $redirect = '/dashboard/admin';
            } elseif ($user->role == 'teacher') {
                $redirect = '/dashboard/teacher';
            } else {
                $redirect = '/dashboard/student';
            }

            // ✅ AJAX RESPONSE
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil',
                    'redirect' => $redirect
                ]);
            }

            // ✅ NON AJAX
            return redirect($redirect);
        } catch (\Illuminate\Validation\ValidationException $e) {

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah'
                ], 422);
            }

            return back()->withErrors([
                'email' => 'Email atau password salah',
            ]);
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
