@extends('layouts.staff')
@section('title','Edit Produk - Staff')

@section('content')
<h2>Edit Produk</h2>

@if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif
@if(session('error'))   <div style="color:red">{{ session('error') }}</div> @endif

<form action="{{ route('staff.wastes.type.update', optional($type ?? null)->id) }}" method="POST" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  <div style="margin-bottom:10px;">
    <label>Kategori</label><br>
    <select name="waste_category_id" required>
      @foreach($categories as $c)
        <option value="{{ $c->id }}"
          {{ old('waste_category_id', optional($type ?? null)->waste_category_id) == $c->id ? 'selected' : '' }}>
          {{ $c->category_name }}
        </option>
      @endforeach
    </select>
    @error('waste_category_id') <div style="color:red">{{ $message }}</div> @enderror
  </div>

  <div style="margin-bottom:10px;">
    <label>Jenis Sampah</label><br>
    <input type="text" name="type_name" value="{{ old('type_name', optional($type ?? null)->type_name) }}" required>
    @error('type_name') <div style="color:red">{{ $message }}</div> @enderror
  </div>

  <div style="margin-bottom:10px;">
    <label>Deskripsi</label><br>
    <textarea name="description">{{ old('description', optional($type ?? null)->description) }}</textarea>
    @error('description') <div style="color:red">{{ $message }}</div> @enderror
  </div>

  <div style="margin-bottom:10px;">
    <label>Harga / Kg</label><br>
    <input type="number" step="0.01" name="price_per_unit" value="{{ old('price_per_unit', optional($type ?? null)->price_per_unit) }}" required>
    @error('price_per_unit') <div style="color:red">{{ $message }}</div> @enderror
  </div>

  <hr>

  <div style="margin-bottom:10px;">
    <label>Penyesuaian Stok</label><br>
    <label><input type="radio" name="adjust_type" value="set" {{ old('adjust_type') == 'add' ? '' : 'checked' }}> Set Nilai</label>
    <label style="margin-left:12px;"><input type="radio" name="adjust_type" value="add" {{ old('adjust_type') == 'add' ? 'checked' : '' }}> Tambah/Kurang</label>
    <div style="margin-top:8px;">
      <input type="number" step="0.01" name="stock_value" value="{{ old('stock_value', 0) }}">
      <small>Stok saat ini: {{ number_format(optional($type->stock ?? null)->available_weight ?? 0, 2, ',', '.') }} Kg</small>
    </div>
    @error('stock_value') <div style="color:red">{{ $message }}</div> @enderror
  </div>

  <div style="margin-bottom:10px;">
    <label>Foto (opsional)</label><br>
    @if(optional($type ?? null)->photo)
      <div style="margin-bottom:6px;">
        <img src="{{ asset('storage/'.optional($type ?? null)->photo) }}" alt="Foto" style="height:60px;">
      </div>
    @endif
    <input type="file" name="photo" accept="image/*">
    @error('photo') <div style="color:red">{{ $message }}</div> @enderror
  </div>

  <button type="submit">Simpan Perubahan</button>
  <a href="{{ route('staff.wastes.index') }}" style="margin-left:8px;">Kembali</a>
</form>
@endsection
