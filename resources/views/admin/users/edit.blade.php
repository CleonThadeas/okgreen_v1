@extends('layouts.admin')

@section('title','Edit Staff')

@section('content')
<div class="card">
  <h2>Edit Staff</h2>

  @if($errors->any()) <div style="color:red"><ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul></div>@endif

  <form action="{{ route('admin.users.update', $staff->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div><label>Nama</label><input type="text" name="name" value="{{ old('name',$staff->name) }}" required></div>
    <div><label>Email</label><input type="email" name="email" value="{{ old('email',$staff->email) }}" required></div>
    <div><label>Password (kosongkan jika tidak ingin mengubah)</label><input type="password" name="password"></div>
    <div><label>Konfirmasi Password</label><input type="password" name="password_confirmation"></div>
    <button type="submit" class="btn">Update</button>
  </form>
</div>
@endsection
