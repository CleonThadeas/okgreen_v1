<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UpdateSessionUserData
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        DB::table('sessions')
            ->where('id', Session::getId())
            ->update([
                'user_id' => $event->user->id ?? null,
                'guard'   => $event->guard ?? null,
            ]);
    }
}
