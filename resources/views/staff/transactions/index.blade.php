@extends('layouts.staff')

@section('title', 'Tambah Kategori Sampah')

@section('content')
<h2>transaction request</h2>
<form method="POST" action="{{ route('staff.transactions.updateStatus', $tx->id) }}">
    @csrf
    <select name="status">
        <option value="pending" {{ $tx->status=='pending'?'selected':'' }}>Pending</option>
        <option value="paid" {{ $tx->status=='paid'?'selected':'' }}>Paid</option>
        <option value="canceling" {{ $tx->status=='canceling'?'selected':'' }}>Canceling</option>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Update</button>
</form>

@endsection