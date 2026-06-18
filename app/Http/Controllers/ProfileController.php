<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the user's profile page (read-only), menampilkan
     * data pribadi sesuai tabel detail masing-masing role:
     * - admin   : tidak ada tabel detail (tbl_admins tidak ada), pakai tbl_users saja
     * - teacher : data dari tbl_teachers (relasi user->teacher)
     * - student : data dari tbl_students (relasi user->student)
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        // $profile berisi data dari tbl_teachers / tbl_students,
        // null untuk admin (karena tidak ada tabel detailnya).
        $profile = match ($user->role) {
            'teacher' => $user->teacher,
            'student' => $user->student,
            default   => null,
        };

        return view('profile.show', [
            'user'    => $user,
            'profile' => $profile,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}