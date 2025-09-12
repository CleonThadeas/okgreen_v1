<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class FCMService
{
    protected $serverKey;

    public function __construct()
    {
        $this->serverKey = config('services.fcm.server_key');
    }

    public function sendNotification($fcmToken, $title, $body, $data = [])
    {
        $url = "https://fcm.googleapis.com/fcm/send";

        $payload = [
            "to" => $fcmToken,
            "notification" => [
                "title" => $title,
                "body"  => $body,
                "sound" => "default"
            ],
            "data" => $data
        ];

        return Http::withHeaders([
            "Authorization" => "key=" . $this->serverKey,
            "Content-Type" => "application/json",
        ])->post($url, $payload)->json();
    }
}
