@extends('layouts.app')

@section('title','Tukar Points')

@section('content')
<div class="container">
    {{-- Sidebar --}}
    @include('user.profile.sidebar')
    <h2>Tukarkan Poin</h2>

    <div style="margin:15px 0; padding:10px; border:1px solid #ccc; background:#f9f9f9;">
        <strong>Total Poin:</strong>
        <span style="font-size:20px; color:green;">
            {{ $userPoints ?? 0 }}
        </span>
    </div>

    <button style="background:#4CAF50; color:white; border:none; padding:8px 15px;">
        Tukarkan Poin
    </button>
</div>
@endsection
