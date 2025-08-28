@extends('layouts.staff')
@section('title','Tambah Produk - Staff')

@section('content')
<h2>Tambah Produk Sampah</h2>

@if($errors->any()) 
<div style="color:red">
  <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<form action="{{ route('staff.wastes.type.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label>Kategori</label>
        <select name="waste_category_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->category_name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>Jenis Sampah</label>
        <input type="text" name="type_name" required>
    </div>

    <div>
        <label>Deskripsi (opsional)</label>
        <textarea name="description"></textarea>
    </div>

    <div>
        <label>Harga per Kg</label>
        <input type="number" name="price_per_unit" required>
    </div>

    <div>
        <label>Stok Awal (Kg)</label>
        <input type="number" name="available_weight" value="0">
    </div>

    <div> 
        <label>Foto Produk</label>
        <input type="file" name="photo" accept="image/*"><br><br>
    </div>

    <button type="submit">Simpan Produk</button>
</form>
@endsection
