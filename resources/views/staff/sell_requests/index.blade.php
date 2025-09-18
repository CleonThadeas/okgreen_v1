@extends('layouts.staff')
@section('title','Permintaan Jual Sampah')

@section('content')
<h2>Permintaan Jual Sampah</h2>

@if(session('success'))
<div style="color:green">{{ session('success') }}</div>
@endif
@if(session('error'))
<div style="color:red">{{ session('error') }}</div>
@endif

<p>
    <a href="{{ route('staff.sell-types.index') }}">Kelola Jenis Sampah Jual</a>
</p>

<table border="1" cellpadding="8" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>User</th>
            <th>Kategori</th>
            <th>Jenis</th>
            <th>Berat (Kg)</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tgl</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($requests as $req)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $req->user->email ?? '-' }}</td>
            <td>{{ $req->category->category_name ?? '-' }}</td>
            <td>{{ $req->sellType->type_name ?? '-' }}</td>
            <td>{{ $req->weight_kg }}</td>
            <td>{{ $req->total_price }}</td>
            <td>{{ ucfirst($req->status) }}</td>
            <td>{{ $req->created_at->format('d M Y H:i') }}</td>
            <td>
                <a href="{{ route('staff.sell_requests.show',$req->id) }}">Lihat</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" align="center">Belum ada permintaan jual.</td></tr>
        @endforelse
    </tbody>
</table>

@endsection