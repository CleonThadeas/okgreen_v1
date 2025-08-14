<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Fortify::authenticateUsing(function (Request $request) {
            // Validasi awal
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $email = $request->email;
            $password = $request->password;

            // 1️⃣ Cek Admin dulu
            if ($admin = Admin::where('email', $email)->first()) {
                if (Hash::check($password, $admin->password)) {
                    config()->set('fortify.guard', 'admin');
                    config()->set('fortify.home', '/admin/dashboard');
                    Auth::guard('admin')->login($admin);
                    return $admin;
                }
            }

            // 2️⃣ Cek Staff
            if ($staff = Staff::where('email', $email)->first()) {
                if (Hash::check($password, $staff->password)) {
                    config()->set('fortify.guard', 'staff');
                    config()->set('fortify.home', '/staff/dashboard');
                    Auth::guard('staff')->login($staff);
                    return $staff;
                }
            }

            // 3️⃣ Cek User
            if ($user = User::where('email', $email)->first()) {
                if (Hash::check($password, $user->password)) {
                    config()->set('fortify.guard', 'web');
                    config()->set('fortify.home', '/dashboard');
                    Auth::guard('web')->login($user);
                    return $user;
                }
            }

            // Kalau semua gagal
            throw ValidationException::withMessages([
                Fortify::username() => __('auth.failed'),
            ]);
        });
    }
}
