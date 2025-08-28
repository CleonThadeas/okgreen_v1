<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    // 🔑 LOGIN ADMIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // bikin token khusus admin
        $token = $admin->createToken('admin_token', ['admin'])->plainTextToken;

        return response()->json([
            'message' => 'Login success',
            'token'   => $token,
        ]);
    }

    // 🏠 DASHBOARD
    public function dashboard()
    {
        return response()->json([
            'message' => 'Welcome to Admin Dashboard'
        ]);
    }

    // ➕ TAMBAH STAFF
    public function storeStaff(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:staff',
            'password' => 'required|min:6',
        ]);

        $staff = Staff::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Staff created',
            'data'    => $staff
        ]);
    }

    // ✏️ UPDATE STAFF
    public function updateStaff(Request $request, $id)
    {
        $staff = Staff::findOrFail($id);
        $staff->update($request->only('name', 'email'));

        return response()->json([
            'message' => 'Staff updated',
            'data'    => $staff
        ]);
    }

    public function listUsers()
    {
        $users = \App\Models\User::all();
        return response()->json(['data' => $users]);
    }


    // ❌ HAPUS STAFF
    public function deleteStaff($id)
    {
        $staff = Staff::findOrFail($id);
        $staff->delete();

        return response()->json(['message' => 'Staff deleted']);
    }
}
