<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk['nama'] ?? 'Detail Produk' }}</title>
    <link rel="stylesheet" href="{{ asset('css/detailbarang.css') }}?v={{ time() }}">
</head>
<body>

@include('partials.header')

<main class="detail-produk-container">

    {{-- Gambar Produk --}}
    <div class="produk-gambar">
        <a href="{{ url()->previous() }}" class="btn-kembali">← back to list</a>
        <img src="{{ asset($produk['gambar'] ?? 'img/sample1.png') }}" alt="{{ $produk['nama'] ?? 'Produk' }}">
    </div>

    {{-- Informasi Produk --}}
    <div class="produk-info">

        {{-- Tag & Nama --}}
        <span class="tag-hotsale">HOTSALE</span>
        <h1>{{ $produk['nama'] ?? 'Nama Produk Contoh' }}</h1>

        {{-- Kategori + Rating --}}
        <div class="kategori-rating">
            <span class="kategori">Cans</span>
            <span class="rating">
                ⭐⭐⭐⭐⭐ <span class="rating-score">4.9</span> (2130 reviews)
            </span>
        </div>

        {{-- Deskripsi --}}
        <p class="deskripsi-label">Deskripsi:</p>
        <div class="deskripsi">
            <img src="{{ asset($produk['gambar'] ?? 'img/sample1.png') }}" alt="thumb" class="thumb-produk">
            <span>{{ $produk['deskripsi'] ?? 'Deskripsi produk belum tersedia.' }}</span>
        </div>

        {{-- Pilihan Size --}}
        <div class="size-container">
            <span>Size:</span>
            <button class="size active">1.5 kg</button>
            <button class="size">1 kg</button>
            <button class="size">500 gr</button>
            <button class="size">250 gr</button>
        </div>
    </div>
</main>

{{-- Bagian bawah sticky --}}
<div class="bottom-bar">
    <div class="bottom-left">
        <img src="{{ asset($produk['gambar'] ?? 'img/sample1.png') }}" alt="thumb" class="thumb-bottom">
        <span class="bottom-nama">{{ $produk['nama'] ?? 'Nama Produk Contoh' }}</span>
    </div>
    <div class="bottom-right">
        <div class="qty-control">
            <button class="qty-btn">-</button>
            <input type="number" value="1" min="1">
            <button class="qty-btn">+</button>
        </div>
        <a href="{{ route('checkout') }}" class="btn-beli">Beli</a>
    </div>
</div>

</body>
</html>
