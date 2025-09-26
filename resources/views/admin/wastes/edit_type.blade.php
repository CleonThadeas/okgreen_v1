@extends('layouts.form')
@section('title','Edit Produk Sampah')

@section('content')
<div class="form-container">
    <div class="product-header">
        <h2>Edit Produk</h2>
    </div>

    <form action="{{ route('staff.wastes.type.update', $type->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Kategori</label>
            <select name="waste_category_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ $c->id == $type->waste_category_id ? 'selected' : '' }}>{{ $c->category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jenis Sampah</label>
                <input type="text" name="type_name" value="{{ old('type_name', $type->type_name) }}" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="description" value="{{ old('description', $type->description) }}">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Harga per Kg</label>
                <input type="number" name="price_per_unit" value="{{ old('price_per_unit', $type->price_per_unit) }}" required>
            </div>
            <div class="form-group">
                <label>Stok Saat Ini</label>
                <input type="text" value="{{ optional($type->stock)->available_weight ?? 0 }}" disabled>
            </div>
        </div>

        <div class="stok-container">
            <label>Mode Perubahan Stok</label>
            <select name="adjust_type" required>
                <option value="add">Tambah/Kurang</option>
                <option value="set">Set Nilai Baru</option>
            </select>
        </div>

        <div class="stok-input">
          <span>Nilai Stok Saat Ini</span>
          <input type="number" name="stock_value" value="0">
        </div>

        <button type="submit" class="btn-confirm">Simpan Perubahan</button>
        <button type="cancel" class="btn-cancel">Batalkan</button>
    </form>
</div>
@endsection
