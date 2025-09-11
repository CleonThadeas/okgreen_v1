@extends('layouts.admin')

@section('title','Edit Produk Sampah')

@section('content')
<div class="card">
  <h2>Edit Produk Sampah</h2>

  @if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif
  @if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif

  <form action="{{ route('admin.wastes.type.update', $type->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- same fields as staff version -->
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
      <label>Jenis Sampah (type_name)</label>
      <input type="text" name="type_name" value="{{ old('type_name', $type->type_name) }}" required>
    </div>

    <div>
      <label>Deskripsi (opsional)</label>
      <textarea name="description">{{ old('description', $type->description) }}</textarea>
    </div>

    <div>
      <label>Harga per Kg</label>
      <input type="number" step="0.01" name="price_per_unit" value="{{ old('price_per_unit', $type->price_per_unit) }}" required>
    </div>

    <div>
      <label>Stok Saat Ini</label>
      <input type="text" value="{{ optional($type->stock)->available_weight ?? 0 }}" disabled>
    </div>

    <div>
      <label>Mode Perubahan Stok</label>
      <select name="adjust_type" required>
        <option value="add">Tambah/Kurang (increment)</option>
        <option value="set">Set (set nilai stok baru)</option>
      </select>
    </div>

    <div>
      <label>Nilai Stok (angka bulat)</label>
      <input type="number" name="stock_value" value="0" required>
      <div class="muted">Jika pilih "Tambah/Kurang" masukkan angka positif untuk tambah, negatif untuk kurangi. Jika pilih "Set", masukkan nilai stok baru (>=0).</div>
    </div>

    <div style="margin-top:12px;">
      <button class="btn" type="submit">Simpan Perubahan</button>
      <a href="{{ route('admin.wastes.index') }}" style="margin-left:12px;">Batal</a>
    </div>
  </form>
</div>
@endsection
