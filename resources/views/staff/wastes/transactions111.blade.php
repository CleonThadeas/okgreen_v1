@extends('layouts.staff')
@section('title','Transaksi untuk: '.$type->type_name)

@section('content')
<div class="container">
  <h2>Transaksi untuk: {{ $type->type_name }}</h2>
  <p>Kategori: {{ $type->category->category_name ?? '-' }}</p>
  <a href="{{ route('staff.wastes.index') }}">&larr; Kembali ke Daftar Produk</a>

  {{-- Search bar --}}
  <form method="GET" action="{{ route('staff.wastes.transactions',$type->id) }}" style="margin:12px 0;">
    <input type="text" name="q" placeholder="Cari username/email..." value="{{ request('q') }}">
    <button type="submit">Cari</button>
  </form>

  @foreach($transactions as $trx)
    <div class="card" style="margin:14px 0; padding:12px;">
      <div style="display:flex; justify-content:space-between;">
        <div>
          <strong>Transaksi #{{ $trx->id }}</strong><br>
          User: {{ $trx->user->name }} ({{ $trx->user->email }})<br>
          Tanggal: {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y H:i') }}
        </div>
        <div style="text-align:right;">
          <div><strong>Rp {{ number_format($trx->total_amount,0,',','.') }}</strong></div>
          <div>Status: <strong>{{ ucfirst($trx->status) }}</strong></div>
        </div>
      </div>

      <table border="1" cellpadding="8" width="100%" style="margin-top:10px;">
        <thead>
          <tr>
            <th>Jenis Sampah</th>
            <th>Qty (Kg)</th>
            <th>Harga/kg</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
          @foreach($trx->items as $item)
            <tr>
              <td>{{ $item->type->type_name ?? '-' }}</td>
              <td align="center">{{ $item->quantity }}</td>
              <td align="right">Rp {{ number_format($item->price_per_unit,0,',','.') }}</td>
              <td align="right">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      {{-- Tombol aksi hanya jika masih pending --}}
      @if($trx->status === 'pending')
        <form action="{{ route('staff.transactions.update',$trx->id) }}" method="POST" style="display:inline;">
          @csrf @method('PUT')
          <input type="hidden" name="status" value="paid">
          <button type="submit" style="background:green; color:white; padding:4px 8px;">✔ Konfirmasi</button>
        </form>
        <form action="{{ route('staff.transactions.update',$trx->id) }}" method="POST" style="display:inline;">
          @csrf @method('PUT')
          <input type="hidden" name="status" value="cancelled">
          <button type="submit" style="background:red; color:white; padding:4px 8px;">✖ Batalkan</button>
        </form>
      @endif
    </div>
  @endforeach
</div>
@endsection
