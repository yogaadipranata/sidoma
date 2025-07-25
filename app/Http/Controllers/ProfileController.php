<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

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
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Validasi data umum
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        // Tambahkan validasi khusus berdasarkan role
        if ($user->role === 'mahasiswa') {
            $request->validate([
                'nim' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            ]);
        } elseif ($user->role === 'dosen') {
            $request->validate([
                'nidn' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            ]);
        }

        // Update data umum
        $user->fill($request->only('name', 'email', 'phone_number', 'address'));

        // Update data spesifik role
        if ($user->role === 'mahasiswa') {
            $user->nim = $request->nim;
        } elseif ($user->role === 'dosen') {
            $user->nidn = $request->nidn;
        }

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
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
