<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ProfileController extends Controller
{
    public function edit()
    {
        // Ambil user dari auth, fallback ke model jika perlu
        $user = Auth::user();
        if (! $user instanceof User) {
            $user = User::find(Auth::id());
        }

        if (! $user) {
            abort(404, 'User not found.');
        }

        return view('user.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        // Pastikan $user adalah Eloquent model
        $user = Auth::user();
        if (! $user instanceof User) {
            $user = User::find(Auth::id());
        }
        if (! $user) {
            return redirect()->back()->withErrors('User tidak ditemukan.');
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            // email tidak diubah
            'phone_number'  => 'nullable|string|max:20',
            // DB enum hanya 'laki-laki' / 'perempuan' -> pilihan "tidak ingin memberitahu" disimpan sebagai NULL
            'gender'        => 'nullable|in:laki-laki,perempuan',
            'date_of_birth' => 'nullable|date',
            'address'       => 'nullable|string',
            'password'      => 'nullable|string|min:8|confirmed',
        ]);

        // assign satu-per-satu (hindari masalah fillable)
        $user->name = $validated['name'];
        $user->phone_number = $validated['phone_number'] ?? null;
        $user->gender = $validated['gender'] ?? null; // kosong => null
        $user->date_of_birth = $validated['date_of_birth'] ?? null;
        $user->address = $validated['address'] ?? null;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
