@extends('layouts.staff')
@section('title','Tambah Produk - Staff')

@section('content')
<h2>Tambah Produk Sampah</h2>

{{-- Tampilkan error validasi --}}
@if($errors->any()) 
<div style="color:red; margin-bottom:10px;">
  <ul>
    @foreach($errors->all() as $e)
      <li>{{ $e }}</li>
    @endforeach
  </ul>
</div>
@endif

<form action="{{ route('staff.wastes.type.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div style="margin-bottom:10px;">
        <label>Kategori</label><br>
        <select name="waste_category_id" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}">{{ $c->category_name }}</option>
            @endforeach
        </select>
    </div>

    <div style="margin-bottom:10px;">
        <label>Jenis Sampah</label><br>
        <input type="text" name="type_name" required value="{{ old('type_name') }}">
    </div>

    <div style="margin-bottom:10px;">
        <label>Deskripsi (opsional)</label><br>
        <textarea name="description">{{ old('description') }}</textarea>
    </div>

    <div style="margin-bottom:10px;">
        <label>Harga per Kg</label><br>
        <input type="number" name="price_per_unit" required step="0.01" value="{{ old('price_per_unit') }}">
    </div>

    <div style="margin-bottom:10px;">
        <label>Stok Awal (Kg)</label><br>
        <input type="number" name="available_weight" step="0.01" value="{{ old('available_weight', 0) }}">
    </div>

    <div style="margin-bottom:10px;"> 
        <label>Foto Produk</label><br>
        <input type="file" name="photo" accept="image/png, image/jpeg, image/jpg">
        <br>
        <small>Format diperbolehkan: JPG, JPEG, PNG (maksimal 5MB)</small>
    </div>

    <button type="submit">Simpan Produk</button>
</form>
@endsection