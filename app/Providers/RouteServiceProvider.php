<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     */
    public const HOME = '/dashboard';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));
        });
    }

    /**
     * Custom redirect logic based on user role.
     */
    public static function redirectBasedOnRole(): string
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => '/admin/dashboard',
            'staff' => '/staff/dashboard',
            'user' => '/user/dashboard',
            default => self::HOME,
        };
    }
}
