@extends('layouts.app')
@section('title','Jual Sampah')

@section('content')
<div class="container">
    <h2>Form Jual Sampah</h2>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div style="color:green">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div style="color:red">{{ session('error') }}</div>
    @endif

    {{-- Form Jual Sampah --}}
    <form action="{{ route('sell-waste.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <p>
            <label>Kategori:</label>
            <select name="waste_category_id" id="categorySelect" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                @endforeach
            </select>
        </p>

        <p>
            <label>Jenis Sampah:</label>
            <select name="sell_waste_type_id" id="typeSelect" required>
                <option value="">-- Pilih Jenis --</option>
            </select>
        </p>

        <p>
            <label>Metode:</label>
            <select name="sell_method" required>
                <option value="drop_point">Drop Point</option>
                <option value="pickup">Pickup</option>
            </select>
        </p>

        <p>
            <label>Berat (Kg/Liter):</label>
            <input type="number" step="0.01" name="weight" required>
        </p>

        <p>
            <label>Foto Sampah:</label>
            <input type="file" name="photo[]" multiple>
        </p>

        <p>
            <label>Deskripsi:</label><br>
            <textarea name="description" rows="3" cols="40"></textarea>
        </p>

        <button type="submit">Kirim Permintaan Jual</button>
    </form>
</div>

{{-- Script AJAX untuk load jenis sesuai kategori --}}
<script>
document.getElementById('categorySelect').addEventListener('change', function(){
    let catId = this.value;
    let typeSelect = document.getElementById('typeSelect');
    typeSelect.innerHTML = '<option value="">Loading...</option>';
    if(!catId) return;

    fetch('/sell-waste/types/' + catId)
        .then(res => res.json())
        .then(data => {
            typeSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
            data.forEach(t => {
                typeSelect.innerHTML += `<option value="${t.id}">${t.type_name} (Poin/Kg: ${t.points_per_kg})</option>`;
            });
        })
        .catch(err => {
            console.error(err);
            typeSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
        });
});
</script>
@endsection
