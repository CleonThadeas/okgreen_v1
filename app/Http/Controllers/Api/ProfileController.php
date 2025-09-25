<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Update Profile API
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'          => 'nullable|string|max:255',
            'email'         => 'nullable|email|unique:users,email,' . $user->id,
            'phone_number'  => 'nullable|string|max:20',
            'address'       => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender'        => 'nullable|string|max:20',
        ]);

        $user->update($request->only([
            'name',
            'email',
            'phone_number',
            'address',
            'date_of_birth',
            'gender',
        ]));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user'    => $user
        ]);
    }
}
