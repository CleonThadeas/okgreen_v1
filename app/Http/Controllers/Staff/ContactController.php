<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\ContactReply;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    /**
     * Tampilkan daftar pesan dari user
     */
    public function index(Request $request)
    {
        $contacts = ContactMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('staff.contact.index', compact('contacts'));
    }

    /**
     * Detail percakapan 1 pesan
     */
    public function show($message_id)
    {
        $contact = ContactMessage::with('replies','user')
            ->where('message_id', $message_id)
            ->firstOrFail();

        return view('staff.contact.show', compact('contact'));
    }

    /**
     * Staff memberikan balasan pertama
     */
    public function reply(Request $request, $message_id)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $contact = ContactMessage::where('message_id', $message_id)->firstOrFail();

        // Pastikan hanya bisa balas sekali
        $alreadyReplied = $contact->replies()->where('sender_role', 'staff')->exists();
        if ($alreadyReplied) {
            return redirect()->route('staff.contacts.show', $contact->message_id)
                ->with('error', 'Pesan sudah pernah dibalas. Silakan edit balasan.');
        }

        ContactReply::create([
            'message_id'  => $contact->message_id,
            'sender_id'   => Auth::guard('staff')->id(),
            'sender_role' => 'staff',
            'message'     => $request->message,
        ]);

        $contact->status = 'replied';
        $contact->save();

        // Kirim notifikasi ke user
        Notification::create([
            'receiver_id'   => $contact->user_id,
            'receiver_role' => 'user',
            'title'         => 'Balasan dari staff',
            'message'       => Str::limit($request->message, 120),
            'is_read'       => false,
        ]);

        return redirect()->route('staff.contacts.show', $contact->message_id)
            ->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Form edit balasan staff
     */

     public function updateReply(Request $r, $message_id, $replyId)

     {
         $r->validate(['message' => 'required|string|max:2000']);
     
         $reply = ContactReply::where('message_id', $message_id)
                     ->where('id', $replyId)
                     ->firstOrFail();
     
         $reply->message = $r->message;
         $reply->save();
     
         return redirect()->route('staff.contacts.show', $message_id)
             ->with('success','Balasan berhasil diperbarui.');
     }
     
}
