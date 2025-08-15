<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;

class MultiGuardLoginController extends Controller
{
    public function create()
    {
        return view('auth.login'); // pakai view Breeze/Fortify bawaan
    }

    public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $email = $request->email;
    $password = $request->password;

    // ADMIN
    if ($admin = Admin::where('email', $email)->first()) {
        if (Hash::check($password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            return redirect()->route('admin.dashboard');
        }
    }

    // STAFF
    if ($staff = Staff::where('email', $email)->first()) {
        if (Hash::check($password, $staff->password)) {
            Auth::guard('staff')->login($staff);
            return redirect()->route('staff.dashboard');
        }
    }

    // USER
    if ($user = User::where('email', $email)->first()) {
        if (Hash::check($password, $user->password)) {
            Auth::guard('web')->login($user);
            return redirect()->route('dashboard');
        }
    }

    throw ValidationException::withMessages([
        'email' => __('auth.failed'),
    ]);
}


    public function destroy(Request $request)
    {
        foreach (['admin', 'staff', 'web'] as $guard) {
            Auth::guard($guard)->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
