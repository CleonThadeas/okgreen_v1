{{-- resources/views/user/belibarang.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Barang</title>
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
    <div id="popup-pilih" class="popup" style="display:none;">
        <div class="popup-content">
            <span class="popup-close" onclick="togglePopup()">&times;</span>
            <h2>Pelajari !</h2>
            <p>Cara memilih beberapa barang untuk checkout:</p>
            <ol>
                <li>Klik button "Tambah" yang terdapat di bawah produk, untuk memilih produk</li>
            </ol>
            <p>Setelah memilih, kamu bisa lanjutkan checkout seperti biasa.</p>
            <button class="popup-button" onclick="togglePopup()">Mengerti</button>
        </div>
    </div>

    {{-- Konten Produk --}}
    <section class="produk-section">
        <h2>Produk Kami</h2>
        <div class="produk-container">
            @forelse($wastes as $waste)
                @php
                    $stok = (int) ($waste->stock->available_weight ?? 0);
                    $price = $waste->price_per_unit ?? 0;
                @endphp
                <div class="produk-card {{ $stok == 0 ? 'stok-habis' : '' }}"
                     data-id="{{ $waste->id }}"
                     data-name="{{ $waste->type_name }}"
                     data-price="{{ $price }}"
                     data-stock="{{ $stok }}"
                     data-deskripsi="{{ $waste->description ?? '' }}">
                    
                    <div class="produk-img-container">
                        <a href="{{ route('detail-barang', ['id' => $waste->id]) }}">
                            @if(!empty($waste->photo))
                                <img src="{{ asset('storage/'.$waste->photo) }}" alt="{{ $waste->type_name }}">
                            @else
                                <img src="{{ asset('img/no-image.png') }}" alt="No Image">
                            @endif
                        </a>
                        @if($stok == 0)
                            <span class="stok-habis-label">Stok Habis</span>
                        @endif
                    </div>

                    <div class="produk-desc">{{ $waste->type_name }}</div>
                    <div class="produk-price">Rp {{ number_format($price, 0, ',', '.') }}</div>
                    <div class="produk-stock">Stok: {{ $stok }}</div>

                    <div class="produk-action">
                        <button class="tambah-btn" {{ $stok == 0 ? 'disabled' : '' }}>
                            {{ $stok == 0 ? 'Tidak Tersedia' : 'Tambah' }}
                        </button>
                    </div>
                </div>
            @empty
                <p>Belum ada produk tersedia.</p>
            @endforelse
        </div>
    </section>

    <!-- Bottom bar -->
    <div class="bottom-bar" id="bottom-bar">
        <span id="selected-count">0 produk dipilih</span>
        <button id="checkout-btn">Checkout</button>
    </div>

    {{-- ===== Inline JS (digabung, fungsi & perilaku sama seperti versi sebelumnya) ===== --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tambahButtons = document.querySelectorAll('.tambah-btn');
        const selectedCountEl = document.getElementById('selected-count');
        const checkoutBtn = document.getElementById('checkout-btn');
        let selectedProducts = new Set();
        let productData = {}; // Menyimpan data detail produk

        // handler klik tombol Tambah/Hapus
        tambahButtons.forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const card = btn.closest('.produk-card');
                const productId = card.dataset.id;
                const productName = card.dataset.name;
                const productImg = card.querySelector('img').src;
                const productDesc = card.dataset.deskripsi || '';
                const stok = parseInt(card.dataset.stock);

                // jika stok 0, beri peringatan (sesuai salah satu versi sebelumnya)
                if (stok === 0) {
                    alert('Produk ini stoknya habis!');
                    return;
                }

                if (selectedProducts.has(productId)) {
                    // hapus pilihan
                    selectedProducts.delete(productId);
                    delete productData[productId];
                    btn.textContent = 'Tambah';
                    btn.style.backgroundColor = ''; 
                } else {
                    // tambah pilihan
                    selectedProducts.add(productId);
                    productData[productId] = {
                        id: productId,
                        nama: productName,
                        gambar: productImg,
                        deskripsi: productDesc
                    };
                    btn.textContent = 'Hapus';
                    btn.style.backgroundColor = '#f44336'; 

                    // Animasi "terbang" ke bottom bar (jika CSS mendukung .fly-image)
                    flyToCart(card.querySelector('img'));
                }

                selectedCountEl.textContent = `${selectedProducts.size} produk dipilih`;
            });
        });

        // fungsi animasi: clone gambar, terbang ke bottom bar, lalu remove
        function flyToCart(imgElement) {
            if (!imgElement) return;
            const bottomBar = document.getElementById('bottom-bar');
            const imgClone = imgElement.cloneNode(true);
            const rect = imgElement.getBoundingClientRect();
            const barRect = bottomBar.getBoundingClientRect();

            imgClone.classList.add('fly-image');
            imgClone.style.position = 'fixed';
            imgClone.style.top = rect.top + 'px';
            imgClone.style.left = rect.left + 'px';
            imgClone.style.width = rect.width + 'px';
            imgClone.style.height = rect.height + 'px';
            imgClone.style.transition = 'all .6s ease-in-out';
            imgClone.style.zIndex = 9999;
            document.body.appendChild(imgClone);

            // lakukan animasi ke posisi bottom bar
            setTimeout(() => {
                imgClone.style.top = (barRect.top + 8) + 'px';
                imgClone.style.left = (barRect.left + barRect.width / 2 - 20) + 'px';
                imgClone.style.width = '30px';
                imgClone.style.height = '30px';
                imgClone.style.opacity = '0.5';
            }, 10);

            setTimeout(() => {
                if (imgClone && imgClone.parentNode) imgClone.parentNode.removeChild(imgClone);
            }, 800);
        }

        // event checkout: jika kosong, alert; jika ada, encode data dan redirect
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function () {
                if (selectedProducts.size === 0) {
                    alert('Pilih minimal 1 produk sebelum checkout.');
                    return;
                }

                // Encode data produk ke JSON & kirim via query string
                const data = encodeURIComponent(JSON.stringify(Object.values(productData)));
                window.location.href = `/co-detail?data=${data}`;
            });
        }

        // toggle popup (tombol "Pilih Beberapa")
        window.togglePopup = function () {
            const popup = document.getElementById('popup-pilih');
            if (!popup) return;
            popup.style.display = (popup.style.display === 'flex' || popup.style.display === 'block') ? 'none' : 'flex';
            // jika ingin posisikan tengah, biarkan CSS menangani; JS di sini hanya toggle display
        };

        // tambahan: pastikan popup close (jika ada tombol lain)
        const popupClose = document.querySelectorAll('.popup-close');
        popupClose.forEach(el => el.addEventListener('click', togglePopup));
    });
    </script>
</body>
</html>
