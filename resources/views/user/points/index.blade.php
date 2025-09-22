@extends('layouts.app')
@section('title','Poin Saya')

@section('content')
<div class="container">
    <h2>Poin Saya</h2>

    <div style="margin:15px 0; padding:10px; border:1px solid #ccc; background:#f9f9f9;">
        <strong>Total Poin:</strong>
        <span style="font-size:20px; color:green;">
            {{ $userPoints->points ?? 0 }}
        </span>
    </div>

    <h3>Riwayat Poin</h3>
    <table border="1" cellpadding="8" width="100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Sumber</th>
                <th>Referensi</th>
                <th>Perubahan</th>
                <th>Deskripsi</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $h)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $h->source }}</td>
                <td>#{{ $h->reference_id }}</td>
                <td style="color:{{ $h->points_change >= 0 ? 'green':'red' }}">
                    {{ $h->points_change }}
                </td>
                <td>{{ $h->description }}</td>
                <td>{{ $h->created_at->format('d M Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="6" align="center">Belum ada riwayat poin.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:10px;">
        {{ $histories->links() }}
    </div>
</div>
@endsection
