@extends('layouts.staff')

@section('title','Dashboard Staff')

@section('content')
<div class="card">
  <h2>Dashboard Staff</h2>
  <p class="muted">Quick links</p>

  <div style="margin-top:12px;">
    <a href="{{ route('staff.wastes.index') }}" class="btn">Kelola Sampah</a>
    <a href="{{ route('staff.sell_requests.index') }}" class="btn">Permintaan Jual</a>
  </div>
</div>
@endsection
