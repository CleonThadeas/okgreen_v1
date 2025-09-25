<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    // List semua contact messages
    public function index(Request $request)
    {
        // ambil data dengan relasi user, urut terbaru dulu, pagination
        $contacts = ContactMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // kirim ke view 'staff.contact.index' sebagai $contacts
        return view('staff.contact.index', compact('contacts'));
    }

    // show single conversation
    public function show($message_id)
    {
        $contact = ContactMessage::with('replies','user')
                     ->where('message_id', $message_id)
                     ->firstOrFail();

        return view('staff.contact.show', compact('contact'));
    }

    // contoh reply (opsional)
    public function reply(Request $r, $message_id)
    {
        $r->validate(['message' => 'required|string']);

        $contact = ContactMessage::where('message_id', $message_id)->firstOrFail();

        \App\Models\ContactReply::create([
            'message_id'  => $contact->message_id,
            'sender_id'   => Auth::guard('staff')->id(),
            'sender_role' => 'staff',
            'message'     => $r->message,
        ]);

        $contact->status = 'replied';
        $contact->save();

        // notifikasi ke user (opsional)
        \App\Models\Notification::create([
            'receiver_id'   => $contact->user_id,
            'receiver_role' => 'user',
            'title'         => 'Balasan dari staff',
            'message'       => \Illuminate\Support\Str::limit($r->message, 120),
            'is_read'       => false,
        ]);

        return redirect()->route('staff.contacts.index')->with('success','Balasan terkirim.');
    }
}
