@extends('layouts.app')
@section('title','Contact Staff')
@section('content')
<div class="container">
    @include('user.profile.sidebar')
    <h2>Hubungi Staff</h2>

    @if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
    @if($errors->any()) <div style="color:red">{{ implode(', ', $errors->all()) }}</div> @endif

    <form action="{{ route('contact.store') }}" method="POST">
        @csrf
        <p><label>Subject</label><br>
            <input type="text" name="subject" value="{{ old('subject') }}" required></p>
        <p><label>Message</label><br>
            <textarea name="message" rows="6" required>{{ old('message') }}</textarea></p>
        <button type="submit">Kirim</button>
    </form>
</div>
@endsection
