<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Notification;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman edit profil
     */
    public function edit()
    {
        $user = Auth::user();
        if (! $user) {
            abort(404, 'User tidak ditemukan.');
        }

        return view('user.profile.edit', compact('user'));
    }

    /**
     * Proses update profil
     */
    public function update(Request $request)
{
    $user = User::find(Auth::id()); // ✅ pastikan ini model User
    if (! $user) {
        return redirect()->back()->withErrors('User tidak ditemukan.');
    }

    $validated = $request->validate([
        'name'          => 'required|string|max:100',
        'phone_number'  => 'nullable|string|max:30',
        'gender'        => 'nullable|in:male,female,other',
        'date_of_birth' => 'nullable|date',
        'address'       => 'nullable|string|max:1000',
        'password'      => 'nullable|string|min:8',
    ]);

    $genderMap = [
        'male'   => 'laki-laki',
        'female' => 'perempuan',
        'other'  => null,
    ];

    $incomingGender = $validated['gender'] ?? null;
    $dbGender = $genderMap[$incomingGender] ?? null;

    $phone = $validated['phone_number'] ?? null;
    if ($phone !== null) {
        $phone = preg_replace('/[^\d\+]/', '', trim($phone));
    }

    // Assign ke model
    $user->name = $validated['name'];
    $user->phone_number = $phone;
    $user->gender = $dbGender;
    $user->date_of_birth = $validated['date_of_birth'] ?? null;
    $user->address = $validated['address'] ?? null;

    if (! empty($validated['password'])) {
        $user->password = Hash::make($validated['password']);
    }

    // ✅ Simpan ke DB
    $user->save();

    // Buat notifikasi
    Notification::create([
        'receiver_id'   => $user->id,
        'receiver_role' => 'user',
        'title'         => 'Profil berhasil diperbarui',
        'message'       => 'Profil Anda berhasil diperbarui pada '. now()->format('d M Y H:i'),
        'is_read'       => false,
    ]);

    return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
}

}
