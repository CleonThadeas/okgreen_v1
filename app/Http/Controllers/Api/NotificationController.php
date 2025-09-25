<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Ambil semua notifikasi user yang login
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $notifications
        ]);
    }

    // Menandai notifikasi sudah dibaca
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('notification_id', $id)
            ->first();

        if (!$notification) {
            return response()->json([
                'status' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->update(['status' => 'read']);

        return response()->json([
            'status' => true,
            'message' => 'Notification marked as read'
        ]);
    }

    // Buat notifikasi baru (dipakai misal setelah transaksi / pickup)
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        $notif = Notification::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'status' => 'unread'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Notification created',
            'data' => $notif
        ], 201);
    }
}
