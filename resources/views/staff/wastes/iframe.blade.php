@extends('layouts.iframe')

@section('title','Daftar Sampah (Iframe)')

@section('content')
<h2>Daftar Sampah (Iframe)</h2>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Foto</th>
            <th>Kategori</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Berat</th>
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
            </tr>
        @empty
            <tr><td colspan="6">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
@endsection
