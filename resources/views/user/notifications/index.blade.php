@extends('layouts.app')

@section('title','Notifikasi')
@vite('resources/css/app.css')
@vite('resources/js/app.js')

@section('content')
<div class="w-full px-6 py-6 bg-gray-50">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Notifikasi</h2>
        <form method="POST" action="{{ route('notifications.readAll') }}">
            @csrf
            <button type="submit" 
                class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                Tandai semua sebagai dibaca
            </button>
        </form>
    </div>

    @if($notifications->isEmpty())
        <div class="bg-white text-gray-500 text-center py-10 rounded-lg border shadow-sm">
            Tidak ada notifikasi saat ini.
        </div>
    @else
        <div class="space-y-4">
            @foreach($notifications as $notif)
            <a href="{{ route('notifications.show', ['id' => $notif->id]) }}"
               class="block rounded-lg border p-5 shadow-sm hover:shadow-md transition
                      {{ $notif->is_read ? 'bg-white border-gray-200' : 'bg-green-50 border-green-300' }}">
                <div class="flex items-start gap-4">
                    <!-- Avatar -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-600 text-white flex items-center justify-center font-semibold">
                        {{ strtoupper(substr($notif->title,0,1)) }}
                    </div>
                    
                    <!-- Konten -->
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ $notif->title }}</h3>
                        <p class="text-gray-600 text-sm">{{ Str::limit($notif->message, 200) }}</p>
                        <span class="text-xs text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                    </div>

                    <!-- Status -->
                    @if(!$notif->is_read)
                        <span class="w-3 h-3 bg-green-600 rounded-full mt-2"></span>
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