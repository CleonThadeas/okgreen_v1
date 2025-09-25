<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * List semua notifikasi staff
     */
    public function index()
    {
        if (!Auth::guard('staff')->check()) {
            abort(403);
        }
        $staff = Auth::guard('staff')->user();

        $notifications = Notification::forReceiver('staff', $staff->id)
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('staff.notifications.index', compact('notifications'));
    }

    /**
     * Detail notifikasi
     */
    public function show($id)
    {
        if (!Auth::guard('staff')->check()) {
            abort(403);
        }
        $staff = Auth::guard('staff')->user();

        $notification = Notification::forReceiver('staff', $staff->id)
            ->where('id', $id)
            ->firstOrFail();

        // Tandai sudah dibaca ketika dibuka
        if (is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
        }

        return view('staff.notifications.show', compact('notification'));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id)
    {
        if (!Auth::guard('staff')->check()) {
            abort(403);
        }
        $staff = Auth::guard('staff')->user();

        $notification = Notification::forReceiver('staff', $staff->id)
            ->where('id', $id)
            ->firstOrFail();

        $notification->update(['read_at' => now()]);

        return redirect()->route('staff.notifications.index')->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        if (!Auth::guard('staff')->check()) {
            abort(403);
        }
        $staff = Auth::guard('staff')->user();

        Notification::forReceiver('staff', $staff->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->route('staff.notifications.index')->with('success', 'Semua notifikasi sudah dibaca.');
    }
}
