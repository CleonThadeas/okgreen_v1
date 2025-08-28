@extends('layouts.staff')
@section('title','Edit Produk Sampah')

@section('content')
<div class="card">
  <h2>Edit Produk Sampah</h2>

  @if($errors->any()) 
    <div style="color:red"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form action="{{ route('staff.wastes.type.update', $type->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
      <label>Kategori</label>
      <select name="waste_category_id" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ $c->id == $type->waste_category_id ? 'selected' : '' }}>{{ $c->category_name }}</option>
        @endforeach
      </select>
    </div>

    <div>
      <label>Jenis Sampah</label>
      <input type="text" name="type_name" value="{{ old('type_name', $type->type_name) }}" required>
    </div>

    <div>
      <label>Deskripsi</label>
      <textarea name="description">{{ old('description', $type->description) }}</textarea>
    </div>

    <div>
      <label>Harga per Kg</label>
      <input type="number" name="price_per_unit" value="{{ old('price_per_unit', $type->price_per_unit) }}" required>
    </div>

    <div>
      <label>Stok Saat Ini</label>
      <input type="text" value="{{ optional($type->stock)->available_weight ?? 0 }}" disabled>
    </div>

    <div>
      <label>Mode Perubahan Stok</label>
      <select name="adjust_type" required>
        <option value="add">Tambah/Kurang</option>
        <option value="set">Set Nilai Baru</option>
      </select>
    </div>

    <div>
      <label>Nilai Stok</label>
      <input type="number" name="stock_value" value="0" required>
    </div>

    <div>
      <label>Foto Produk</label><br>
      @if($type->photo)
        <img src="{{ asset('storage/'.$type->photo) }}" width="120"><br>
      @endif
      <input type="file" name="photo" accept="image/*">
    </div>

    <div style="margin-top:12px;">
      <button type="submit">Simpan Perubahan</button>
      <a href="{{ route('staff.wastes.index') }}" style="margin-left:12px;">Batal</a>
    </div>
  </form>
</div>
@endsection
