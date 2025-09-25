<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Penjualan</title>
    <link rel="stylesheet" href="{{ asset('css/sell-history.css') }}?v={{ time() }}">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

    <div class="jual-container animate-card">
        <h2 class="title">Riwayat Penjualan Saya</h2>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        <div class="table-wrapper">
            <table class="riwayat-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kategori</th>
                        <th>Jenis</th>
                        <th>Berat (Kg)</th>
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
                            <td>
                                <span class="badge 
                                    {{ $s->status == 'pending' ? 'badge-pending' : '' }}
                                    {{ $s->status == 'approved' ? 'badge-success' : '' }}
                                    {{ $s->status == 'rejected' ? 'badge-danger' : '' }}">
                                    {{ ucfirst($s->status) }}
                                </span>
                            </td>
                            <td>{{ $s->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="no-data">Belum ada penjualan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
