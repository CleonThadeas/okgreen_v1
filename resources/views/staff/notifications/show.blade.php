@extends('layouts.staff')

@section('title','Detail Notifikasi Staff')

@section('content')
<div class="container">
    <h2>{{ $notification->title }}</h2>
    <p>{{ $notification->message }}</p>
    <p><small>Diterima pada: {{ $notification->created_at->format('d M Y H:i') }}</small></p>

    <a href="{{ route('staff.notifications.index') }}">← Kembali ke daftar</a>
</div>
@endsection
