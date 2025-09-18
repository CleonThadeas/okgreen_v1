<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Staff;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Actions\Fortify\CreateNewUser; // Pastikan file ini ada: app/Actions/Fortify/CreateNewUser.php

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Binding agar Fortify tahu bagaimana membuat user baru (register)
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

        // Binding respons setelah login -> redirect berdasarkan guard/role
        $this->app->singleton(LoginResponse::class, function ($app) {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    // Periksa guard yang aktif (updateSession sudah login-kan ke guard spesifik)
                    if (Auth::guard('admin')->check()) {
                        return redirect()->intended('/admin/dashboard');
                    }

                    if (Auth::guard('staff')->check()) {
                        return redirect()->intended('/staff/dashboard');
                    }

                    // Default user (web)
                    return redirect()->intended('/dashboard');
                }
            };
        });

        // Binding respons setelah register -> redirect berdasarkan guard/role (biasanya web)
        $this->app->singleton(RegisterResponse::class, function ($app) {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    if (Auth::guard('admin')->check()) {
                        return redirect()->intended('/admin/dashboard');
                    }

                    if (Auth::guard('staff')->check()) {
                        return redirect()->intended('/staff/dashboard');
                    }

                    return redirect()->intended('/dashboard');
                }
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pastikan view login & register ada supaya Fortify tidak error
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        /**
         * Custom authentication multi-guard
         * (tetap menggunakan logic yang sudah kamu punya)
         */
        Fortify::authenticateUsing(function (Request $request) {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $email = $request->email;
            $password = $request->password;

            // Helper untuk login & simpan guard di sessions
            $updateSession = function ($user, $guard) {
                config()->set('fortify.guard', $guard);
                Auth::guard($guard)->login($user);

                // Update tabel sessions agar informasi guard & user_id tersimpan
                DB::table('sessions')
                    ->where('id', session()->getId())
                    ->update([
                        'user_id' => $user->id,
                        'guard' => $guard
                    ]);
            };

            // Admin
            if ($admin = Admin::where('email', $email)->first()) {
                if (Hash::check($password, $admin->password)) {
                    $updateSession($admin, 'admin');
                    return $admin;
                }
            }

            // Staff
            if ($staff = Staff::where('email', $email)->first()) {
                if (Hash::check($password, $staff->password)) {
                    $updateSession($staff, 'staff');
                    return $staff;
                }
            }

            // User (web)
            if ($user = User::where('email', $email)->first()) {
                if (Hash::check($password, $user->password)) {
                    $updateSession($user, 'web');
                    return $user;
                }
            }

            throw ValidationException::withMessages([
                Fortify::username() => __('auth.failed'),
            ]);
        });
    }
}
