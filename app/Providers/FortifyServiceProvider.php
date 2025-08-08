<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::authenticateUsing(function ($request) {
            $email = $request->email;
            $password = $request->password;

            // Cek di tabel users
            if ($user = User::where('email', $email)->first()) {
                if (Hash::check($password, $user->password)) {
                    Auth::guard('web')->login($user);
                    return $user;
                }
            }

            // Cek di tabel admins
            if ($admin = Admin::where('email', $email)->first()) {
                if (Hash::check($password, $admin->password)) {
                    Auth::guard('admin')->login($admin);
                    return $admin;
                }
            }

            // Cek di tabel staff
            if ($staff = Staff::where('email', $email)->first()) {
                if (Hash::check($password, $staff->password)) {
                    Auth::guard('staff')->login($staff);
                    return $staff;
                }
            }

            throw ValidationException::withMessages([
                Fortify::username() => __('auth.failed'),
            ]);
        });
    }
}
    