@extends('layouts.admin')
@section('title','Kelola Sampah - Admin')

@section('content')
<h2>Kelola Sampah (Admin)</h2>

@if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
@if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif

<p>
    <a href="{{ route('admin.wastes.category.create') }}">+ Tambah Kategori</a> |
    <a href="{{ route('admin.wastes.type.create') }}">+ Tambah Produk</a>
</p>

<table border="1" cellpadding="8" width="100%">
    <thead>
      <tr>
        <th>#</th>
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
                <td>{{ $w->category->category_name ?? '-' }}</td>
                <td>{{ $w->type_name }}</td>
                <td>Rp {{ number_format($w->price_per_unit ?? 0,0,',','.') }}</td>
                <td>{{ $w->stock->available_weight ?? 0 }}</td>
                <td>
                  @if(Route::has('admin.wastes.type.edit'))
                    <a href="{{ route('admin.wastes.type.edit', $w->id) }}" style="padding:6px 8px; background:#0b5d39; color:#fff; border-radius:6px; text-decoration:none;">Edit</a>
                  @else
                    <span class="muted">Edit (route belum ada)</span>
                  @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
