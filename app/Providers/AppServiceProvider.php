<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
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
        // Fix untuk error key length saat migrate MySQL versi lama
        Schema::defaultStringLength(191);

        // Override Fortify Login Response untuk multi-guard redirect
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    if (Auth::guard('admin')->check()) {
                        return redirect()->route('admin.dashboard');
                    }
                    if (Auth::guard('staff')->check()) {
                        return redirect()->route('staff.dashboard');
                    }
                    return redirect()->route('dashboard');
                }
            };
        });
        
    }
}
