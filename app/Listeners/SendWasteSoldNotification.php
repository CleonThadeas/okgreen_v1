<?php

namespace App\Listeners;

use App\Events\WasteSold;
use App\Models\Notification;

class SendWasteSoldNotification
{
    public function handle(WasteSold $event)
    {
        Notification::create([
            'user_id' => $event->userId,
            'message' => "Transaksi berhasil! Saldo sebesar Rp " . number_format($event->amount, 0, ',', '.') . " telah masuk ke akun kamu.",
            'status' => 'unread',
        ]);
    }
}
