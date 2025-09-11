@extends('layouts.app')

@section('title','Riwayat Transaksi')

@section('content')
<div class="container">
  <h2>Riwayat Transaksi</h2>

  @if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
  @if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif

  @if($transactions->isEmpty())
    <div class="card">
      <p>Belum ada transaksi.</p>
    </div>
  @else
    @foreach($transactions as $trx)
      <div class="card" style="margin-bottom:14px;">
        <div style="display:flex; justify-content:space-between; align-items:center;">
          <div>
            <strong>Transaksi #{{ $trx->id }}</strong>
            <div class="muted">Tanggal: {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y H:i') }}</div>
          </div>

          <div style="text-align:right;">
            <div style="font-size:1.1rem;"><strong>Rp {{ number_format($trx->total_amount,0,',','.') }}</strong></div>
            <div class="muted">Status: {{ ucfirst($trx->status) }}</div>
          </div>
        </div>

        <hr style="margin:12px 0; border:none; border-top:1px solid #eee;">

        {{-- Daftar item transaksi --}}
        <div style="overflow-x:auto;">
          <table border="0" cellpadding="8" cellspacing="0" width="100%">
            <thead style="background:#f6f7f8;">
              <tr>
                <th align="left">Jenis Sampah</th>
                <th align="center">Qty (Kg)</th>
                <th align="right">Harga/kg</th>
                <th align="right">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @foreach($trx->items as $item)
                <tr>
                  <td>
                    {{ optional($item->type)->type_name ?? 'Tipe (ID: '.$item->waste_type_id.')' }}
                    @if(optional($item->type)->category)
                      <div class="muted" style="font-size:0.85rem;">Kategori: {{ $item->type->category->category_name }}</div>
                    @endif
                  </td>
                  <td align="center">{{ $item->quantity }}</td>
                  <td align="right">Rp {{ number_format($item->price_per_unit,0,',','.') }}</td>
                  <td align="right">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Optional: ringkasan per transaksi --}}
        <div style="margin-top:10px; display:flex; justify-content:flex-end; gap:20px; align-items:center;">
          <div class="muted">Subtotal: Rp {{ number_format(collect($trx->items)->sum('subtotal'),0,',','.') }}</div>
          {{-- Jika kamu menyimpan ongkir di transaksi, tampilkan: --}}
          @if(isset($trx->shipping_cost))
            <div class="muted">Ongkir: Rp {{ number_format($trx->shipping_cost,0,',','.') }}</div>
          @endif
          <div><strong>Total: Rp {{ number_format($trx->total_amount,0,',','.') }}</strong></div>
        </div>

      </div>
    @endforeach
  @endif
</div>
@endsection
