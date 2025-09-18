@extends('layouts.staff')
@section('title','Tambah Jenis Sampah Jual')

@section('content')
<h2>Tambah Jenis Sampah Jual</h2>

@if($errors->any())
<div style="color:red;">
    <ul>
        @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('staff.sell-types.store') }}">
    @csrf
    <p>
        <label>Kategori:</label>
        <select name="waste_category_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->category_name }}</option>
            @endforeach
        </select>
    </p>

    <p>
        <label>Jenis Sampah:</label>
        <input type="text" name="type_name" required>
    </p>

    <p>
        <label>Poin / Kg:</label>
        <input type="number" step="0.01" name="points_per_kg" required>
    </p>

    <p>
        <button type="submit">Simpan</button>
        <a href="{{ route('staff.sell-types.index') }}">Kembali</a>
    </p>
</form>
@endsection
