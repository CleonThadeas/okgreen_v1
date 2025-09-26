<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UpdateSessionUserData
{
    /**
     * Handle the event when a user logs in.
     *
     * @param \Illuminate\Auth\Events\Login $event
     */
    public function handle(Login $event): void
    {
        // Siapkan data update untuk sessions
        $updateData = [
            'guard' => $event->guard ?? null, // selalu simpan guard
        ];

        // Tentukan kolom mana yang dipakai sesuai guard
        switch ($event->guard) {
            case 'web': // User biasa
                $updateData['user_id'] = $event->user->id ?? null;
                $updateData['admin_id'] = null;
                $updateData['staff_id'] = null;
                break;

            case 'admin': // Admin
                $updateData['admin_id'] = $event->user->id ?? null;
                $updateData['user_id'] = null;
                $updateData['staff_id'] = null;
                break;

            case 'staff': // Staff
                $updateData['staff_id'] = $event->user->id ?? null;
                $updateData['user_id'] = null;
                $updateData['admin_id'] = null;
                break;
        }

        // Update data session di tabel sessions
        DB::table('sessions')
            ->where('id', Session::getId())
            ->update($updateData);
    }
}
