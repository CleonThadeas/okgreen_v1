@extends('layouts.staff')

@section('title','Daftar Pesan')

@section('content')
<h2>Inbox Pesan</h2>

@if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif

<table border="0" width="100%" cellpadding="8">
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Waktu</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($contacts as $c)
            <tr>
                <td>{{ $loop->iteration + ($contacts->currentPage()-1)*$contacts->perPage() }}</td>
                <td>{{ optional($c->user)->name ?? 'User #'.$c->user_id }}</td>
                <td>{{ $c->subject }}</td>
                <td>{{ ucfirst($c->status) }}</td>
                <td>{{ $c->created_at->format('d M Y H:i') }}</td>
                <td><a href="{{ route('staff.contacts.show', $c->message_id) }}">Buka</a></td>
            </tr>
        @empty
            <tr><td colspan="6" align="center">Belum ada pesan.</td></tr>
        @endforelse
    </tbody>
</table>

<div style="margin-top:12px;">
    {{ $contacts->links() }}
</div>
@endsection
