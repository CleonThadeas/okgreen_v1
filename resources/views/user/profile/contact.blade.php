@extends('layouts.app')
@section('title','Contact Us')

@section('content')
<div class="d-flex">
    @include('user.profile.sidebar')

    <div class="flex-grow-1 p-4">
        <h3>Hubungi Kami</h3>
        <form>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" class="form-control">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control">
            </div>
            <div class="mb-3">
                <label>Pesan</label>
                <textarea class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Kirim</button>
        </form>
    </div>
</div>
@endsection
