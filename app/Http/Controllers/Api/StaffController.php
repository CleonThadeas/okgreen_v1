<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    // ======================
    // AUTH
    // ======================

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $staff = Staff::where('email', $request->email)->first();

        if (!$staff || !Hash::check($request->password, $staff->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        // Buat token Sanctum
        $token = $staff->createToken('staff_token', ['staff'])->plainTextToken;

        return response()->json([
            'message'    => 'Login berhasil',
            'staff'      => $staff,
            'token'      => $token,
            'token_type' => 'Bearer'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }


    // ======================
    // CRUD STAFF (Admin Only)
    // ======================

    // Ambil semua staff
    public function index()
    {
        return response()->json(Staff::all());
    }

    // Detail staff
    public function show($id)
    {
        $staff = Staff::find($id);
        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }
        return response()->json($staff);
    }

    // Tambah staff
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100',
            'email'        => 'required|email|unique:staff,email',
            'password'     => 'required|min:6',
            'phone_number' => 'sometimes|nullable|string|max:15',
            'gender'       => 'sometimes|nullable|in:laki-laki,perempuan',
        ]);

        $staff = Staff::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'phone_number'      => $validated['phone_number'] ?? null,
            'gender'            => $validated['gender'] ?? null,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'message' => 'Staff created successfully',
            'data'    => $staff
        ], 201);
    }

    // Update staff
    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $validated = $request->validate([
            'name'         => 'sometimes|string|max:100',
            'email'        => 'sometimes|email|unique:staff,email,' . $id,
            'password'     => 'sometimes|min:6',
            'phone_number' => 'sometimes|nullable|string|max:15',
            'gender'       => 'sometimes|nullable|in:laki-laki,perempuan',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $staff->update($validated);

        return response()->json([
            'message' => 'Staff updated successfully',
            'data'    => $staff
        ]);
    }

    // Hapus staff
    public function destroy($id)
    {
        $staff = Staff::find($id);
        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $staff->delete();
        return response()->json(['message' => 'Staff deleted successfully']);
    }
}
