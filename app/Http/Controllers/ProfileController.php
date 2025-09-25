<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        $user = Auth::user();
        if (! $user) {
            return redirect()->back()->withErrors('User tidak ditemukan.');
        }

        // Validasi input (sesuaikan aturan bila perlu)
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'phone_number'  => 'nullable|string|max:30',
            // nilai yg dikirim dari form tetap 'male','female','other' (user-facing)
            'gender'        => 'nullable|in:male,female,other',
            // gunakan field DB yang memang ada: date_of_birth
            'date_of_birth' => 'nullable|date',
            'address'       => 'nullable|string|max:1000',
            // password opsional, tanpa confirmation (atau tambahkan confirmation di form & ubah rule)
            'password'      => 'nullable|string|min:8',
        ]);

        // Mapping dari nilai form ke nilai yang diterima DB (sesuaikan enum DB-mu)
        // Di DB kamu pakai 'laki-laki' dan 'perempuan' (sebelumnya muncul truncate)
        $genderMap = [
            'male'   => 'laki-laki',
            'female' => 'perempuan',
            'other'  => null,        // 'other' disimpan sebagai NULL (tidak memberitahu)
        ];

        $incomingGender = $validated['gender'] ?? null;
        $dbGender = array_key_exists($incomingGender, $genderMap) ? $genderMap[$incomingGender] : null;

        // Sanitasi nomor telepon: hapus karakter non-digit kecuali leading +
        $phone = $validated['phone_number'] ?? null;
        if ($phone !== null) {
            // contoh: +6289... atau 089...
            $phone = trim($phone);
            // biarkan + jika ada di depan, hilangkan spasi/kurung/dash
            $phone = preg_replace('/[^\d\+]/', '', $phone);
        }

        // Assign ke model
        $user->name = $validated['name'];
        $user->phone_number = $phone;
        $user->gender = $dbGender;                    // nilai sudah mapped
        $user->date_of_birth = $validated['date_of_birth'] ?? null;
        $user->address = $validated['address'] ?? null;

        // Password (hanya bila diisi)
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        // Simpan
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
