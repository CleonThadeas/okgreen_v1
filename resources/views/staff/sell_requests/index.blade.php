@extends('layouts.staff')
@section('title','Permintaan Jual')

@section('content')
  <div class="card">
    <h2>Permintaan Jual</h2>
    <p>Daftar permintaan penjualan (sementara kosong).</p>

    @if(empty($requests))
      <div>Belum ada permintaan.</div>
    @else
      {{-- loop $requests --}}
    @endif
  </div>
@endsection
