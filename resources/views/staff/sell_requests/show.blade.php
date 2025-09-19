@extends('layouts.staff')
@section('title','Detail Permintaan Jual')

@section('content')
<h2>Detail Permintaan Jual</h2>

@if(session('success'))
<div style="color:green">{{ session('success') }}</div>
@endif
@if(session('error'))
<div style="color:red">{{ session('error') }}</div>
@endif

<p><strong>User:</strong> {{ $req->user->name }} ({{ $req->user->email }})</p>
<p><strong>Kategori:</strong> {{ $req->category->category_name ?? '-' }}</p>
<p><strong>Jenis:</strong> {{ $req->sellType->type_name ?? '-' }}</p>
<p><strong>Berat:</strong> {{ $req->weight_kg }} Kg</p>
<p><strong>Total:</strong> {{ $req->total_price }}</p>
<p><strong>Status:</strong> {{ ucfirst($req->status) }}</p>
<p><strong>Tanggal:</strong> {{ $req->created_at->format('d M Y H:i') }}</p>

<h3>Foto Sampah</h3>
@if($req->photos && $req->photos->count())
    @foreach($req->photos as $p)
        <img src="{{ asset('storage/'.$p->photo_path) }}" width="150" style="margin:5px;">
    @endforeach
@else
    <p>Tidak ada foto.</p>
@endif

<hr>

<h3>Update Status</h3>
<form action="{{ route('staff.sell_requests.update', $req->id) }}" method="POST">
    @csrf
    <label>
        <input type="radio" name="status" value="approved" required> Setujui
    </label>
    <label>
        <input type="radio" name="status" value="canceled" required> Batalkan
    </label>
    <button type="submit">Update</button>
</form>

@endsection
