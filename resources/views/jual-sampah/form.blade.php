@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Form Jual Sampah</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('sell-waste.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Kategori Sampah</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Jenis Sampah</label>
            <select name="type_id" class="form-control" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach($types as $type)
                    <option value="{{ $type->type_id }}">{{ $type->type_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Berat Sampah (kg)</label>
            <input type="number" step="0.1" name="weight_kg" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Harga per Kg (Rp)</label>
            <input type="number" step="0.1" name="price_per_kg" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Total Harga</label>
            <input type="number" step="0.1" name="total_price" class="form-control" readonly>
        </div>

        <div class="form-group">
            <label>Upload Foto Sampah (opsional)</label>
            <input type="file" name="foto_sampah" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-success mt-3">Kirim Penjualan</button>
    </form>
</div>

<script>
    const weightInput = document.querySelector('input[name="weight_kg"]');
    const priceInput = document.querySelector('input[name="price_per_kg"]');
    const totalInput = document.querySelector('input[name="total_price"]');

    function updateTotal() {
        const weight = parseFloat(weightInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalInput.value = weight * price;
    }

    weightInput.addEventListener('input', updateTotal);
    priceInput.addEventListener('input', updateTotal);
</script>
@endsection
