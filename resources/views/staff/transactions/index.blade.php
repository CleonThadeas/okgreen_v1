@extends('layouts.staff')
@section('title','Daftar Transaksi User')

@section('content')
<h2>Daftar Transaksi User</h2>

@if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
@if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif

<table border="1" cellpadding="8" width="100%" style="border-collapse:collapse;">
  <thead>
    <tr>
      <th>ID</th>
      <th>Pembeli</th>
      <th>Email</th>
      <th>Total</th>
      <th>Status</th>
      <th>Tanggal</th>
      <th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($txs as $t)
      <tr>
        <td>#{{ $t->id }}</td>
        <td>{{ $t->user->name ?? '-' }}</td>
        <td>{{ $t->user->email ?? '-' }}</td>
        <td>Rp {{ number_format($t->total_amount,0,',','.') }}</td>
        <td>{{ ucfirst($t->status) }}</td>
        <td>{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y H:i') }}</td>
        <td>
          <a href="{{ route('staff.transactions.show',$t->id) }}">Detail</a>
        </td>
      </tr>
    @empty
      <tr><td colspan="7">Belum ada transaksi.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection
