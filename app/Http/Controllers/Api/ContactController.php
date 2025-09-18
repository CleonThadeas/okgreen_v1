<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ContactMessage;
use App\Models\ContactReply;

class ContactController extends Controller
{
    // =============================
    // USER: Kirim pesan ke staff
    // =============================
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = ContactMessage::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim',
            'data'    => $contact,
        ]);
    }

    // =============================
    // STAFF: Lihat semua pesan user
    // =============================
    public function index()
    {
        // Asumsinya staff yang login bisa lihat semua pesan
        $messages = ContactMessage::with('user')->latest()->get();

        return response()->json([
            'success' => true,
            'data'    => $messages,
        ]);
    }

    // =============================
    // USER & STAFF: Lihat detail chat + balasan
    // =============================
    public function show(Request $request, $messageId)
    {
        $message = ContactMessage::with([
            'user',
            'replies.staff',
            'replies.user'
        ])->findOrFail($messageId);

        return response()->json([
            'success' => true,
            'auth_id' => $request->user()->id, 
            'data'    => $message,
        ]);
    }

    // =============================
    // USER & STAFF: Balas pesan
    // =============================
    public function reply(Request $request, $messageId)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);

        if (Auth::guard('staff')->check()) {
            // Staff membalas
            $reply = ContactReply::create([
                'message_id'  => $messageId,
                'staff_id'    => Auth::guard('staff')->id(),
                'reply'       => $request->reply,
                'sender_type' => 'staff',
            ]);
        } else {
            // User membalas
            $reply = ContactReply::create([
                'message_id'  => $messageId,
                'user_id'     => Auth::id(),
                'reply'       => $request->reply,
                'sender_type' => 'user',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Balasan berhasil dikirim',
            'data'    => $reply,
        ]);
    }
}
