<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard');
        } elseif (Auth::guard('staff')->check()) {
            return redirect('/staff/dashboard');
        } else {
            return redirect('/dashboard');
        }
    }
}
