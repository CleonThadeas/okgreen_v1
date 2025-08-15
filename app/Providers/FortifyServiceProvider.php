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
        // Multi-guard authentication
        Fortify::authenticateUsing(function (Request $request) {

            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $email = $request->email;
            $password = $request->password;

            // 1️⃣ Cek Admin
            if ($admin = Admin::where('email', $email)->first()) {
                if (Hash::check($password, $admin->password)) {
                    Auth::shouldUse('admin');
                    Auth::guard('admin')->login($admin);
                    session()->put('redirect_after_login', '/admin/dashboard');
                    return $admin;
                }
            }

            // 2️⃣ Cek Staff
            if ($staff = Staff::where('email', $email)->first()) {
                if (Hash::check($password, $staff->password)) {
                    Auth::shouldUse('staff');
                    Auth::guard('staff')->login($staff);
                    session()->put('redirect_after_login', '/staff/dashboard');
                    return $staff;
                }
            }

            // 3️⃣ Cek User
            if ($user = User::where('email', $email)->first()) {
                if (Hash::check($password, $user->password)) {
                    Auth::shouldUse('web');
                    Auth::guard('web')->login($user);
                    session()->put('redirect_after_login', '/dashboard');
                    return $user;
                }
            }

            // Gagal semua
            throw ValidationException::withMessages([
                Fortify::username() => __('auth.failed'),
            ]);
        });

        // Custom redirect setelah login
        $this->app->singleton(\Laravel\Fortify\Contracts\LoginResponse::class, function () {
            return new class implements \Laravel\Fortify\Contracts\LoginResponse {
                public function toResponse($request)
                {
                    $redirect = session()->pull('redirect_after_login', '/dashboard');
                    return redirect()->intended($redirect);
                }
            };
        });
    }
}
