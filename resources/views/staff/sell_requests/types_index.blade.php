@extends('layouts.staff')
@section('title','Kelola Jenis Sampah Jual')

@section('content')
<h2>Kelola Jenis Sampah Jual</h2>

@if(session('success'))
<div style="color:green">{{ session('success') }}</div>
@endif

<p>
    <a href="{{ route('staff.sell-types.create') }}">+ Tambah Jenis Sampah Jual</a>
</p>

<table border="1" cellpadding="8" width="100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Kategori</th>
            <th>Jenis</th>
            <th>Poin / Kg</th>
        </tr>
    </thead>
    <tbody>
        @forelse($types as $t)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $t->category->category_name ?? '-' }}</td>
            <td>{{ $t->type_name }}</td>
            <td>{{ $t->points_per_kg }}</td>
        </tr>
        @empty
        <tr><td colspan="4" align="center">Belum ada jenis sampah jual.</td></tr>
        @endforelse
    </tbody>
</table>

<p><a href="{{ route('staff.sell_requests.index') }}">← Kembali ke Permintaan Jual</a></p>

@endsection
