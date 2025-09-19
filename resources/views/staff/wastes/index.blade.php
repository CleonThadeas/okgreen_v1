@extends('layouts.staff')
@section('title','Kelola Sampah - Staff')

@section('content')
<h2>Kelola Sampah (Staff)</h2>

{{-- Notifikasi --}}
@if(session('success')) 
  <div style="padding:10px; background:#d4edda; color:#155724; border:1px solid #c3e6cb; margin-bottom:10px;">
    {{ session('success') }}
  </div>
@endif

@if(session('error')) 
  <div style="padding:10px; background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; margin-bottom:10px;">
    {{ session('error') }}
  </div>
@endif

<p style="margin-bottom: 15px;">
    <a href="{{ route('staff.wastes.category.create') }}">+ Tambah Kategori</a> |
    <a href="{{ route('staff.wastes.type.create') }}">+ Tambah Produk</a> |
    <a href="{{ route('staff.transactions.index') }}">📦 Lihat Transaksi</a>
</p>

<table border="1" cellpadding="8" width="100%" style="border-collapse:collapse; text-align:center;">
    <thead style="background:#f2f2f2;">
      <tr>
        <th style="width:40px;">#</th>  
        <th style="width:80px;">Foto</th>
        <th>Kategori</th>
        <th>Jenis</th>
        <th>Harga/kg</th>
        <th>Stok (Kg)</th>
        <th style="width:160px;">Aksi</th>
      </tr>
    </thead>
    <tbody>
        @forelse($wastes as $w)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  @if(!empty($w->photo))
                    <img src="{{ asset('storage/'.$w->photo) }}" 
                         alt="{{ $w->type_name }}" 
                         style="max-height:60px; max-width:80px; object-fit:cover; border-radius:4px;">
                  @else
                    <span style="color:#888;">(No Image)</span>
                  @endif
                </td>
                <td>{{ optional($w->category)->category_name ?? '-' }}</td>
                <td>{{ $w->type_name }}</td>
                <td>Rp {{ number_format($w->price_per_unit ?? 0,0,',','.') }}</td>
                <td>{{ number_format(optional($w->stock)->available_weight ?? 0, 2, ',', '.') }}</td>
                <td>
                    {{-- Tombol Edit --}}
                    <a href="{{ route('staff.wastes.type.edit', $w->id) }}" style="margin-right:6px;">✏️ Edit</a>
                    
                    {{-- Tombol Hapus --}}
                    <form action="{{ route('staff.wastes.type.delete', $w->id) }}" 
                          method="POST" 
                          style="display:inline;" 
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                style="background:none; border:none; color:red; cursor:pointer;">
                            🗑️ Hapus
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" style="padding:20px; color:#888;">Belum ada data produk.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
