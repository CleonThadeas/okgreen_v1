@extends('layouts.app')

@section('title','Detail Notifikasi')

@section('content')
<div class="container">
    <h2>{{ $notification->title }}</h2>
    <p>{{ $notification->message }}</p>
    <p><small>Diterima pada: {{ $notification->created_at->format('d M Y H:i') }}</small></p>

    <a href="{{ route('notifications.index') }}">← Kembali ke daftar</a>
</div>
@endsection
