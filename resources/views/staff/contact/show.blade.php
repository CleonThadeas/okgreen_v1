@extends('layouts.staff')
@section('title','Conversation')
@section('content')
<div class="container">
    <h2>Pesan: {{ $contact->subject }}</h2>
    <div style="border:1px solid #000000; padding:12px; margin-bottom:10px;">
        <strong>{{ $contact->user->name ?? 'User #' . $contact->user_id }}</strong><br>
        <div>{{ $contact->message }}</div>
        <div class="muted">{{ $contact->created_at->format('d M Y H:i') }}</div>
    </div>

    <h3>Balasan</h3>
    @foreach($contact->replies as $r)
        <div style="border:1px solid #eee; padding:8px; margin-bottom:6px; background:#fafafa;">
            <strong>{{ $r->sender_role == 'staff' ? 'Staff' : ($r->sender_id ? 'User' : 'Pengirim') }}</strong>
            <div>{{ $r->message }}</div>
            <div class="muted">{{ $r->created_at->format('d M Y H:i') }}</div>
        </div>
    @endforeach

    <h4>Balas</h4>
    <form action="{{ route('staff.contacts.reply', $contact->message_id) }}" method="POST">
        @csrf
        <textarea name="message" rows="4" required></textarea><br>
        <button type="submit">Kirim Balasan</button>
    </form>
</div>
@endsection
