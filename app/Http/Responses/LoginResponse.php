<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;
use App\Actions\Fortify\RedirectsUsers;

class LoginResponse implements LoginResponseContract
{
    use RedirectsUsers;

    /**
     * Handle the login response.
     */
    public function toResponse($request)
    {
        return redirect()->intended($this->redirectPath());
    }
}
