@extends('layouts.staff')
@section('title','Kelola Sampah - Staff')

@section('content')
<h2>Kelola Sampah (Staff)</h2>

@if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
@if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif

<p>
    <a href="{{ route('staff.wastes.category.create') }}">+ Tambah Kategori</a> |
    <a href="{{ route('staff.wastes.type.create') }}">+ Tambah Produk</a>
</p>

<table border="1" cellpadding="8" width="100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Foto</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Harga/kg</th>
        <th>Stok (Kg)</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse($wastes as $w)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  @if($w->photo)
                    <img src="{{ asset('storage/'.$w->photo) }}" width="80">
                  @else
                    <span>Tidak ada foto</span>
                  @endif
                </td>
                <td>{{ $w->category->category_name ?? '-' }}</td>
                <td>{{ $w->type_name }}</td>
                <td>Rp {{ number_format($w->price_per_unit ?? 0,0,',','.') }}</td>
                <td>{{ $w->stock->available_weight ?? 0 }}</td>
                <td>
                    <a href="{{ route('staff.wastes.type.edit', $w->id) }}">Edit</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="7">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
