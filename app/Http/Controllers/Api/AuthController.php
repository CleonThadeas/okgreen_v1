<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'email_verified_at' => now(),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ], 201);
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // User Info
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    // Update Profile
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validated = $request->validate([
            'name'          => 'sometimes|string|max:100',
            'email'         => 'sometimes|string|email|unique:users,email,' . $user->id,
            'password'      => 'sometimes|string|min:6',
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|string|in:laki-laki,perempuan',
        ]);

        // Kalau ada password, hash dulu
        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        // Update semua field tervalidasi
        $user->fill($validated)->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user->fresh()
        ]);
    }
}
