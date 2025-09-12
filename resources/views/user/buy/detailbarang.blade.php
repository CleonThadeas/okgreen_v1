<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->type_name ?? 'Detail Produk' }}</title>
    <link rel="stylesheet" href="{{ asset('css/detailbarang.css') }}?v={{ time() }}">
</head>
<body>

@include('partials.header')

<main class="detail-produk-container">
    {{-- Gambar Produk --}}
    <div class="produk-gambar">
        <a href="{{ route('buy-waste.index') }}" class="btn-kembali">← back to list</a>

        @if(!empty($produk->photo))
            <img src="{{ asset('storage/'.$produk->photo) }}?v={{ time() }}" alt="{{ $produk->type_name ?? 'Produk' }}">
        @else
            <img src="{{ asset('img/no-image.png') }}" alt="Produk">
        @endif
    </div>

    {{-- Informasi Produk --}}
    <div class="produk-info">
        <span class="tag-hotsale">HOTSALE</span>
        <h1>{{ $produk->type_name }}</h1>

        <div class="kategori-rating">
            <span class="kategori">{{ $produk->category->category_name ?? '-' }}</span>
            <span class="rating">
                ⭐⭐⭐⭐⭐ <span class="rating-score">4.9</span> (2130 reviews)
            </span>
        </div>

        <div class="deskripsi">
            @if(!empty($produk->photo))
                <img src="{{ asset('storage/'.$produk->photo) }}" alt="thumb" class="thumb-produk">
            @else
                <img src="{{ asset('img/no-image.png') }}" alt="thumb" class="thumb-produk">
            @endif
            <span>{{ $produk->description ?? 'Deskripsi produk belum tersedia.' }}</span>
        </div>

        {{-- Pilihan Size --}}
        @if($produk->stock && $produk->stock->available_weight > 0)
        <div class="size-container">
            <label for="custom-size">Masukkan Berat (Kg):</label>
            <div class="qty-control">
                <button type="button" class="qty-btn" id="decrease-size">-</button>
                <input 
                    type="number" 
                    id="custom-size" 
                    class="size-input" 
                    name="size" 
                    value="1" 
                    min="1" 
                    step="1" 
                    readonly>
                <button type="button" class="qty-btn" id="increase-size">+</button>
            </div>
            <small class="note">*Minimal 1 Kg</small>

            <!-- hidden untuk display -->
            <input type="hidden" id="display-size" value="1">
            <input type="hidden" id="display-price" value="{{ $produk->price }}">
        </div>
        @endif
    </div>
</main>

{{-- Bottom bar --}}
<div class="bottom-bar">
    <div class="bottom-left">
        @if(!empty($produk->photo))
            <img src="{{ asset('storage/'.$produk->photo) }}" alt="thumb" class="thumb-bottom">
        @else
            <img src="{{ asset('img/no-image.png') }}" alt="thumb" class="thumb-bottom">
        @endif
        <span class="bottom-nama">{{ $produk->type_name }}</span>
    </div>
    <div class="bottom-right">
        <span class="bottom-harga" id="total-harga">
            Rp {{ number_format($produk->price, 0, ',', '.') }}
        </span>

        <form action="{{ route('checkout.prepare') }}" method="POST" id="checkout-form">
            @csrf
            <input type="hidden" name="product_id" value="{{ $produk->id }}">
            <input type="hidden" name="qty" value="1">

            <!-- hidden untuk checkout -->
            <input type="hidden" name="size" id="form-size" value="1">
            <input type="hidden" name="total_price" id="form-price" value="{{ $produk->price }}">

            <button type="submit" class="btn-beli">Beli</button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log("Detail produk JS loaded");

    const basePrice = {{ $produk->price }};
    const sizeInput = document.getElementById('custom-size');
    const displaySize = document.getElementById('display-size');
    const displayPrice = document.getElementById('display-price');
    const totalPriceField = document.getElementById('total-harga');
    const formSize = document.getElementById('form-size');
    const formPrice = document.getElementById('form-price');
    const increaseBtn = document.getElementById('increase-size');
    const decreaseBtn = document.getElementById('decrease-size');

    function updateTotal() {
        let weight = parseInt(sizeInput.value) || 1;
        if (weight < 1) weight = 1;

        // update input & hidden
        sizeInput.value = weight;
        displaySize.value = weight;
        displayPrice.value = basePrice * weight;

        // update hidden form
        formSize.value = weight;
        formPrice.value = basePrice * weight;

        // update tampilan harga
        totalPriceField.textContent = "Rp " + (basePrice * weight).toLocaleString('id-ID');
    }

    if (increaseBtn) {
        increaseBtn.addEventListener('click', () => {
            sizeInput.value = parseInt(sizeInput.value) + 1;
            updateTotal();
        });
    }

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', () => {
            if (parseInt(sizeInput.value) > 1) {
                sizeInput.value = parseInt(sizeInput.value) - 1;
                updateTotal();
            }
        });
    }

    // cegah ketik manual
    sizeInput.addEventListener('keydown', e => e.preventDefault());

    // set harga awal
    updateTotal();
});
</script>

</body>
</html>
