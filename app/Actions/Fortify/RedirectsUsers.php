<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;

trait RedirectsUsers
{
    protected function redirectPath()
    {
        $user = Auth::user();

        // Kalau login sebagai Admin
        if ($user && $user instanceof \App\Models\Admin) {
            return '/admin/dashboard';
        }

        // Kalau login sebagai Staff
        if ($user && $user instanceof \App\Models\Staff) {
            return '/staff/dashboard';
        }

        // Default: User
        return '/user/dashboard';
    }
}
