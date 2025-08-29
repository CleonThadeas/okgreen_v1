<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk</title>
    <link rel="stylesheet" href="{{ asset('css/belibarang.css') }}?v={{ time() }}">
</head>
<body>

    {{-- Include header --}}
    @include('partials.header')

    <!-- Tombol Pilih Beberapa -->
    <div class="pilih-beberapa-btn" onclick="togglePopup()">
        <img src="{{ asset('img/info.png') }}" alt="Pilih" class="icon-pilih">
        Pilih Beberapa
    </div>

    <!-- Popup Tutorial -->
    <div id="popup-pilih" class="popup">
        <div class="popup-content">
            <span class="popup-close" onclick="togglePopup()">&times;</span>
            <h2>Pelajari !</h2>
            <p>Cara memilih beberapa barang untuk checkout:</p>
            <ol>
                <li>Klik button "Tambah" yang terdapat di bawah produk, untuk memilih produk </li>
            </ol>
            <p>Setelah memilih, kamu bisa lanjutkan checkout seperti biasa.</p>
            <button class="popup-button" onclick="togglePopup()">Mengerti</button>
        </div>
    </div>

    {{-- Konten Produk --}}
   <section class="produk-section">
    <h2>Produk Kami</h2>
    <div class="produk-container">
        @for ($i = 1; $i <= 12; $i++)
        @php
            // contoh stok (acak), nanti bisa diganti dari DB
            $stok = rand(0, 5); 
        @endphp
        <div class="produk-card {{ $stok == 0 ? 'stok-habis' : '' }}" 
             data-id="{{ $i }}" 
             data-name="Nama Produk {{ $i }}" 
             data-price="{{ 100000 * $i }}" 
             data-stock="{{ $stok }}">
            
            <div class="produk-img-container">
                <a href="{{ route('detail-barang', ['id' => $i]) }}">
                    <img src="{{ asset("img/sample$i.png") }}" alt="Produk {{ $i }}">
                </a>
                @if($stok == 0)
                    <span class="stok-habis-label">Stok Habis</span>
                @endif
            </div>
            
            <div class="produk-desc">Nama Produk {{ $i }}</div>
            <div class="produk-price">Rp {{ number_format(100000 * $i, 0, ',', '.') }}</div>
            <div class="produk-stock">Stok: {{ $stok }}</div>
            
            <div class="produk-action">
                <button class="tambah-btn" {{ $stok == 0 ? 'disabled' : '' }}>
                    {{ $stok == 0 ? 'Tidak Tersedia' : 'Tambah' }}
                </button>
            </div>
        </div>
        @endfor
    </div>
</section>

<!-- Bottom bar -->
<div class="bottom-bar" id="bottom-bar">
    <span id="selected-count">0 produk dipilih</span>
    <button id="checkout-btn">Checkout</button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tambahButtons = document.querySelectorAll('.tambah-btn');
    const selectedCountEl = document.getElementById('selected-count');
    let selectedProducts = new Set();
    let productData = {}; // Menyimpan data detail produk

    tambahButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const card = btn.closest('.produk-card');
            const productId = card.dataset.id;
            const productName = card.dataset.nama;
            const productImg = card.querySelector('img').src;
            const productDesc = card.dataset.deskripsi || '';
            const img = card.querySelector('img');

            if (selectedProducts.has(productId)) {
                selectedProducts.delete(productId);
                delete productData[productId];
                btn.textContent = 'Tambah';
                btn.style.backgroundColor = ''; 
            } else {
                selectedProducts.add(productId);
                productData[productId] = {
                    id: productId,
                    nama: productName,
                    gambar: productImg,
                    deskripsi: productDesc
                };
                btn.textContent = 'Hapus';
                btn.style.backgroundColor = '#f44336'; 

                // 🔹 Animasi "terbang" ke bottom bar
                flyToCart(img);
            }

            selectedCountEl.textContent = `${selectedProducts.size} produk dipilih`;
        });
    });

    function flyToCart(imgElement) {
        const bottomBar = document.getElementById('bottom-bar');
        const imgClone = imgElement.cloneNode(true);
        const rect = imgElement.getBoundingClientRect();
        const barRect = bottomBar.getBoundingClientRect();

        imgClone.classList.add('fly-image');
        imgClone.style.top = rect.top + 'px';
        imgClone.style.left = rect.left + 'px';
        document.body.appendChild(imgClone);

        setTimeout(() => {
            imgClone.style.top = (barRect.top + 10) + 'px';
            imgClone.style.left = (barRect.left + barRect.width / 2 - 40) + 'px';
            imgClone.style.width = '30px';
            imgClone.style.height = '30px';
            imgClone.style.opacity = '0.5';
        }, 10);

        setTimeout(() => {
            imgClone.remove();
        }, 800);
    }

    // 🔹 Saat checkout
    document.getElementById('checkout-btn').addEventListener('click', function () {
        if (selectedProducts.size === 0) {
            alert('Pilih minimal 1 produk sebelum checkout.');
            return;
        }

        // Encode data produk ke JSON & kirim via query string
        const data = encodeURIComponent(JSON.stringify(Object.values(productData)));
        window.location.href = `/co-detail?data=${data}`;
    });

    window.togglePopup = function () {
        const popup = document.getElementById('popup-pilih');
        popup.style.display = (popup.style.display === 'flex') ? 'none' : 'flex';
    }
});
</script>

<script>
    tambahButtons.forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        const card = btn.closest('.produk-card');
        const productId = card.dataset.id;
        const productName = card.dataset.name;
        const productImg = card.querySelector('img').src;
        const productDesc = card.dataset.deskripsi || '';
        const stok = parseInt(card.dataset.stock);

        if (stok === 0) {
            alert('Produk ini stoknya habis!');
            return;
        }

        if (selectedProducts.has(productId)) {
            selectedProducts.delete(productId);
            delete productData[productId];
            btn.textContent = 'Tambah';
            btn.style.backgroundColor = ''; 
        } else {
            selectedProducts.add(productId);
            productData[productId] = {
                id: productId,
                nama: productName,
                gambar: productImg,
                deskripsi: productDesc
            };
            btn.textContent = 'Hapus';
            btn.style.backgroundColor = '#f44336'; 
            flyToCart(card.querySelector('img'));
        }

        selectedCountEl.textContent = `${selectedProducts.size} produk dipilih`;
    });
});

</script>

</body>
</html>
