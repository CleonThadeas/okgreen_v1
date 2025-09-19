@extends('layouts.staff')
@section('title','Kelola Sampah - Staff')

@section('content')
@if(session('success')) 
    <div style="color:green">{{ session('success') }}</div> 
@endif
@if(session('error')) 
    <div style="color:red">{{ session('error') }}</div> 
@endif

<div class="container">
    <div class="header">
        <h2>Kelola Sampah (Staff)</h2>
    </div>
        <a href="{{ route('admin.wastes.type.create') }}" class="btn-add">+ Tambah Produk</a>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Foto</th>
                <th>Kategori</th>
                <th>Nama Produk</th>
                <th>Harga</th>
                <th>Berat</th>
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
                    <td>{{ $w->stock->available_weight ?? 0 }} kg</td>
                    <td>
                        <a href="{{ route('staff.wastes.type.edit', $w->id) }}" style="padding:6px 8px; background:#0b5d39; color:#fff; border-radius:6px; text-decoration:none;">Edit</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7">Belum ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
