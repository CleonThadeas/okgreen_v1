<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $notifications = Notification::forReceiver('user', $userId)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('user.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $userId = Auth::id();
        $notification = Notification::forReceiver('user', $userId)->findOrFail($id);

        if (! $notification->is_read) {
            $notification->markRead();
        }

        return view('user.notifications.show', compact('notification'));
    }

    public function markAsRead($id)
    {
        $userId = Auth::id();
        $notification = Notification::forReceiver('user', $userId)->findOrFail($id);
        $notification->markRead();

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function markAllAsRead()
    {
        $userId = Auth::id();
        Notification::forReceiver('user', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
