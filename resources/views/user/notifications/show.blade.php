@extends('layouts.app')

@section('title','Detail Notifikasi')
@vite('resources/css/app.css')
@vite('resources/js/app.js')
@section('content')
<div class="w-full px-6 py-6 bg-gray-50">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Detail Notifikasi</h2>
        <a href="{{ route('notifications.index') }}" 
           class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300 transition">
           ← Kembali
        </a>
    </div>

    <!-- Card Notifikasi -->
    <div class="bg-white rounded-lg shadow-md border p-6">
        <div class="flex items-start gap-4">
            
            <!-- Avatar -->
            <div class="flex-shrink-0 w-14 h-14 rounded-full bg-green-600 text-white flex items-center justify-center font-semibold text-lg">
                {{ strtoupper(substr($notification->title,0,1)) }}
            </div>

            <!-- Konten -->
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-gray-800">{{ $notification->title }}</h3>
                <p class="text-gray-600 mt-2">{{ $notification->message }}</p>
                <p class="text-xs text-gray-400 mt-3">Diterima pada {{ $notification->created_at->format('d M Y H:i') }}</p>
            </div>

            <!-- Status -->
            @if(!$notification->is_read)
                <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Belum Dibaca</span>
            @else
                <span class="px-3 py-1 text-xs font-medium bg-gray-200 text-gray-600 rounded-full">Sudah Dibaca</span>
            @endif
        </div>
    </div>

</div>
@endsection
