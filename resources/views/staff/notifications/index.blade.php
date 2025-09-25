@extends('layouts.staff')

@section('title','Notifikasi Staff')

@section('content')
<div class="container">
    <h2>Notifikasi Staff</h2>

    <form method="POST" action="{{ route('staff.notifications.readAll') }}">
        @csrf
        <button type="submit" style="margin-bottom:10px;">Tandai Semua Dibaca</button>
    </form>

    @if($notifications->isEmpty())
        <p>Tidak ada notifikasi.</p>
    @else
        <ul style="list-style:none; padding:0;">
            @foreach($notifications as $notif)
                <li style="padding:10px; border-bottom:1px solid #ddd; {{ $notif->is_read ? 'background:#f9f9f9;' : 'background:#e8f7ff;' }}">
                    <a href="{{ route('staff.notifications.show', $notif->id) }}" style="text-decoration:none; color:#333;">
                        <strong>{{ $notif->title }}</strong><br>
                        <small>{{ $notif->created_at->format('d M Y H:i') }}</small>
                    </a>
                </li>
            @endforeach
        </ul>
        {{ $notifications->links() }}
    @endif
</div>
@endsection
