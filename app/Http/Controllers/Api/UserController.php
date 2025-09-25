<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Ambil semua user
    public function index()
    {
        return response()->json(User::all());
    }

    // Ambil detail user
    public function show($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    // Tambah user
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:100',
            'email'         => 'required|email|unique:users',
            'password'      => 'required|min:6',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|string|max:10',
        ]);

        $user = User::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'phone_number'      => $validated['phone_number'] ?? null,
            'address'           => $validated['address'] ?? null,
            'date_of_birth'     => $validated['date_of_birth'] ?? null,
            'gender'            => $validated['gender'] ?? null,
            'email_verified_at' => now(),
        ]);

        return response()->json($user, 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validated = $request->validate([
            'name'            => 'sometimes|string|max:100',
            'email'           => 'sometimes|email|unique:users,email,'.$id,
            'password'        => 'sometimes|min:6',
            'phone_number'    => 'sometimes|string|max:20',
            'address'         => 'sometimes|string|max:255',
            'date_of_birth'   => 'sometimes|date',
            'gender'          => 'sometimes|string|max:10',
            'email_verified_at' => 'sometimes|date',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Kalau belum pernah diverifikasi & tidak dikirim lewat request → otomatis isi sekarang
        if (!$user->email_verified_at && !isset($validated['email_verified_at'])) {
            $validated['email_verified_at'] = now();
        }

        $user->update($validated);

        return response()->json($user);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
