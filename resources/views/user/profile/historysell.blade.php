@extends('layouts.app')

@section('title','Riwayat Penjualan')

@section('content')
<div class="container">
    {{-- Sidebar --}}
    @include('user.profile.sidebar')

    <h2>Riwayat Penjualan Saya</h2>

    <table border="1" cellpadding="8" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Kategori</th>
                <th>Jenis</th>
                <th>Berat</th>
                <th>Poin / Kg</th>
                <th>Total Poin</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sells as $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->category->category_name ?? '-' }}</td>
                <td>{{ $s->sellType->type_name ?? '-' }}</td>
                <td>{{ $s->weight_kg }}</td>
                <td>{{ $s->price_per_kg }}</td>
                <td>{{ $s->points_awarded ?? 0 }}</td>
                <td>{{ ucfirst($s->status) }}</td>
                <td>{{ $s->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" align="center">Belum ada penjualan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
