@extends('layouts.staff')
@section('title','Detail Pesan')

@section('content')
<div class="w-full bg-white shadow rounded-lg p-6 animate-fade">

    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">Pesan Masuk</h2>

    {{-- Pesan Utama --}}
    <div class="w-full border rounded-lg p-6 mb-8 bg-gray-50">
        <div class="font-semibold text-green-700 text-lg">{{ $contact->user->name ?? 'User #'.$contact->user_id }}</div>
        <p class="mt-3 text-gray-700 whitespace-pre-line text-base leading-relaxed">
            {{ $contact->message }}
        </p>
        <small class="block mt-2 text-gray-500">{{ $contact->created_at->format('d M Y H:i') }}</small>
    </div>

    {{-- Balasan Staff --}}
    @if($contact->replies->isNotEmpty())
        <h3 class="text-xl font-semibold text-gray-800 mb-3">Balasan Staff</h3>
        @php $reply = $contact->replies->first(); @endphp
        <div class="w-full border rounded-lg p-6 bg-green-50">
            <div class="font-medium text-green-700 text-lg">Staff</div>
            <p class="mt-3 text-gray-700 whitespace-pre-line text-base leading-relaxed">
                {{ $reply->message }}
            </p>
            <small class="block mt-2 text-gray-500">{{ $reply->created_at->format('d M Y H:i') }}</small>
        </div>

    @else
        {{-- Jika belum ada balasan, tampilkan form jawab --}}
        <h3 class="text-xl font-semibold text-gray-800 mb-3">Balas Pesan</h3>
        <form action="{{ route('staff.contacts.reply', $contact->message_id) }}" method="POST" class="w-full">
            @csrf
            <textarea name="message" rows="6" required
                      class="w-full border rounded-lg p-4 focus:ring-2 focus:ring-green-400 focus:outline-none text-base"
                      placeholder="Tulis jawaban untuk user..."></textarea>
            <button type="submit" 
                    class="mt-3 bg-green-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                Kirim Balasan
            </button>
        </form>
    @endif
</div>
@endsection

@push('styles')
<style>
@keyframes fadeIn {
  from {opacity:0; transform:translateY(10px);}
  to {opacity:1; transform:translateY(0);}
}
.animate-fade { animation: fadeIn 0.6s ease-in-out; }
</style>
@endpush

@push('scripts')
<script>
function toggleEditForm(){
    let form = document.getElementById("edit-form");
    if(form){
        form.classList.toggle("hidden");
    }
}
</script>
@endpush