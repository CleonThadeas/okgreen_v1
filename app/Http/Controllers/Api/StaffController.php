<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Staff;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
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

    // Tambah staff (HANYA ADMIN)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:staff',
            'password' => 'required|min:6',
        ]);

        $staff = Staff::create([
            'name'              => $validated['name'],
            'email'             => $validated['email'],
            'password'          => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        return response()->json($staff, 201);
    }

    // Update staff
    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        if (!$staff) {
            return response()->json(['message' => 'Staff not found'], 404);
        }

        $validated = $request->validate([
            'name'     => 'sometimes|string|max:100',
            'email'    => 'sometimes|email|unique:staff,email,'.$id,
            'password' => 'sometimes|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $staff->update($validated);

        return response()->json($staff);
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
