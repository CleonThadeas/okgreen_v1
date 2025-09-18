@extends('layouts.admin')

@section('title','Tambah Staff')

@section('content')
<div class="card">
  <h2>Tambah Staff</h2>

  @if($errors->any()) <div style="color:red"><ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul></div>@endif

  <form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div><label>Nama</label><input type="text" name="name" value="{{ old('name') }}" required></div>
    <div><label>Email</label><input type="email" name="email" value="{{ old('email') }}" required></div>
    <div><label>Password</label><input type="password" name="password" required></div>
    <div><label>Konfirmasi Password</label><input type="password" name="password_confirmation" required></div>
    <button type="submit" class="btn">Simpan</button>
  </form>
</div>
@endsection
