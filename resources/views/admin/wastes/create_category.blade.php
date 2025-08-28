@extends('layouts.admin')

@section('title', 'Tambah Kategori Sampah')

@section('content')
<h2>Tambah Kategori Sampah</h2>

<form action="{{ route('admin.wastes.category.store') }}" method="POST">
    @csrf
    <div>
        <label for="category_name">Nama Kategori</label>
        <input type="text" name="category_name" id="category_name" required>
    </div>
    <button type="submit">Simpan</button>
</form>
@endsection
