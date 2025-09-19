@extends('layouts.staff')
@section('title','Detail Transaksi')

@section('content')
<h2>Detail Transaksi #{{ $tx->id }}</h2>

<p><strong>Pembeli:</strong> {{ $tx->user->name }} ({{ $tx->user->email }})</p>
<p><strong>Total:</strong> Rp {{ number_format($tx->total_amount,0,',','.') }}</p>
<p><strong>Status:</strong> {{ ucfirst($tx->status) }}</p>
<p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y H:i') }}</p>

<h3>Item</h3>
<table border="1" cellpadding="6" width="100%" style="border-collapse:collapse;">
  <thead>
    <tr>
      <th>Jenis</th>
      <th>Kategori</th>
      <th>Qty</th>
      <th>Harga/kg</th>
      <th>Subtotal</th>
    </tr>
  </thead>
  <tbody>
    @foreach($tx->items as $it)
      <tr>
        <td>{{ $it->type->type_name }}</td>
        <td>{{ $it->type->category->category_name ?? '-' }}</td>
        <td>{{ $it->quantity }}</td>
        <td>Rp {{ number_format($it->price_per_unit,0,',','.') }}</td>
        <td>Rp {{ number_format($it->subtotal,0,',','.') }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

<form action="{{ route('staff.transactions.update',$tx->id) }}" method="POST" style="margin-top:20px;">
  @csrf
  <label>Status:</label>
  <select name="status">
    <option value="pending" {{ $tx->status=='pending'?'selected':'' }}>Pending</option>
    <option value="paid" {{ $tx->status=='paid'?'selected':'' }}>Paid</option>
    <option value="cancelled" {{ $tx->status=='cancelled'?'selected':'' }}>Cancelled</option>
  </select>
  <button type="submit">Update</button>
</form>
@endsection
