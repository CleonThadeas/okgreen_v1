<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Notification;
use App\Models\Staff;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    // show form
    public function create()
    {
        return view('user.profile.contact'); // buat blade ini
    }

    // store new contact message
    public function store(Request $r)
    {
        $r->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $contact = ContactMessage::create([
            'user_id' => Auth::id(),
            'subject' => $r->subject,
            'message' => $r->message,
            'status'  => 'pending',
        ]);

        // Notify all staff (or you can notify admins only)
        $staffs = Staff::all();
        foreach ($staffs as $st) {
            Notification::create([
                'receiver_id'   => $st->id,
                'receiver_role' => 'staff',
                'title'         => 'Pesan baru dari user',
                'message'       => 'User '.Auth::user()->name.' mengirim: '.Str::limit($r->message, 120),
                'is_read'       => false,
            ]);
        }

        return redirect()->route('contact')->with('success','Pesan berhasil dikirim. Tim staff akan membalas.');
    }
}
