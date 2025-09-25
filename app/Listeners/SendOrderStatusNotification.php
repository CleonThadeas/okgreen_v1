<?php

namespace App\Listeners;

use App\Events\OrderStatusUpdated;
use App\Services\FCMService;

class SendOrderStatusNotification
{
    public function handle(OrderStatusUpdated $event)
    {
        $user = $event->order->user;

        $title = "Update Pesanan";
        $message = match ($event->order->status) {
            'accepted'   => "Pengepul sudah menerima pesananmu. Tunggu penjemputan ya!",
            'processing' => "Sampahmu sedang diproses oleh tim pengepul.",
            'completed'  => "Transaksi selesai.",
            'cancelled'  => "Pesananmu dibatalkan. Silakan buat pesanan baru.",
            'arrived'    => "Pengepul sudah sampai.",
            'reward'     => "Selamat! Kamu dapat bonus 10 poin karena telah menjual.",
            default      => "Status pesanan berubah.",
        };

        if ($user->fcm_token) {
            (new FCMService())->sendNotification(
                $user->fcm_token,
                $title,
                $message,
                [
                    'order_id' => $event->order->id,
                    'status'   => $event->order->status,
                ]
            );
        }
    }
}
