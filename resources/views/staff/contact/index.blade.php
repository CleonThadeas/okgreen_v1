@extends('layouts.staff')

@section('title','Daftar Pesan')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-4">Inbox Pesan</h2>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded animate-fade">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm text-gray-700">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">#</th>
                    <th class="px-4 py-3 text-left">User</th>
                    <th class="px-4 py-3 text-left">Subject</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Waktu</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $c)
                <tr class="border-b hover:bg-green-50 transition duration-300 ease-in-out">
                    <td class="px-4 py-3">{{ $loop->iteration + ($contacts->currentPage()-1)*$contacts->perPage() }}</td>
                    <td class="px-4 py-3 font-medium">{{ optional($c->user)->name ?? 'User #'.$c->user_id }}</td>
                    <td class="px-4 py-3">{{ $c->subject }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $c->status == 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ ucfirst($c->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-500">{{ $c->created_at->format('d M Y H:i') }}</td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('staff.contacts.show', $c->message_id) }}"
                           class="text-green-600 hover:text-green-800 font-semibold transition duration-200">
                           Buka
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada pesan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
</div>

{{-- Animasi fade-in --}}
<style>
@keyframes fadeIn {
  from {opacity:0; transform:translateY(10px);}
  to {opacity:1; transform:translateY(0);}
}
.animate-fade { animation: fadeIn 0.6s ease-in-out; }
</style>
@endsection
    