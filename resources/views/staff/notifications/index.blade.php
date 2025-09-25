@extends('layouts.staff')

@section('title','Notifikasi Staff')
@vite('resources/css/app.css')
@vite('resources/js/app.js')
@section('content')
<div class="w-full px-6 py-6 bg-gray-50">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Notifikasi Staff</h2>
        <form method="POST" action="{{ route('staff.notifications.readAll') }}">
            @csrf
            <button type="submit" 
                class="px-4 py-2 bg-green-700 text-white text-sm rounded-lg hover:bg-green-800 transition">
                Tandai Semua Dibaca
            </button>
        </form>
    </div>

    <!-- List Notifikasi -->
    @if($notifications->isEmpty())
        <p class="text-gray-500">Tidak ada notifikasi.</p>
    @else
        <div class="space-y-4">
            @foreach($notifications as $notif)
                <a href="{{ route('staff.notifications.show', $notif->id) }}" 
                   class="block bg-white border rounded-lg shadow-sm hover:shadow-md transition p-5 w-full {{ $notif->is_read ? 'opacity-80' : '' }}">
                    <div class="flex items-start gap-4">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-700 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr($notif->title,0,1)) }}
                        </div>

                        <!-- Content -->
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $notif->title }}</h3>
                            <p class="text-gray-600 text-sm mt-1">{{ Str::limit($notif->message, 80) }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>

                        <!-- Status -->
                        @if(!$notif->is_read)
                            <span class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full">Baru</span>
                        @endif
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
