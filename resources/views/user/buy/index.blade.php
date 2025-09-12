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

    {{-- Header --}}
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
                <li>Klik button "Tambah" di bawah produk.</li>
                <li>Produk yang dipilih akan muncul hitungannya di bawah.</li>
                <li>Klik "Checkout" untuk melanjutkan.</li>
            </ol>
            <button class="popup-button" onclick="togglePopup()">Mengerti</button>
        </div>
    </div>

    {{-- Daftar Produk --}}
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
                        <a href="javascript:void(0)" onclick="openDetailModal({{ $waste->id }})">
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

    <!-- Modal Detail Produk -->
    <div id="detailModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
        background:rgba(0,0,0,0.6); z-index:1000; align-items:center; justify-content:center;">
      <div style="background:#fff; padding:20px; border-radius:8px; width:600px; max-height:80%; overflow:auto; position:relative;">
        <span onclick="closeDetailModal()" style="position:absolute; top:10px; right:15px; cursor:pointer; font-size:22px;">&times;</span>
        
        <div id="modalPhotos" style="text-align:center; margin-bottom:12px;"></div>
        <h3 id="modalTypeName"></h3>
        <p><strong>Kategori:</strong> <span id="modalCategory"></span></p>
        <p><strong>Stok Tersedia:</strong> <span id="modalStock"></span> Kg</p>
        <p><strong>Harga:</strong> Rp <span id="modalPrice"></span> /Kg</p>
        <p><strong>Dibeli:</strong> <span id="modalTimesBought"></span> kali
           <span id="modalStars"></span></p>
      </div>
    </div>

    {{-- Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const tambahButtons = document.querySelectorAll('.tambah-btn');
        const selectedCountEl = document.getElementById('selected-count');
        const checkoutBtn = document.getElementById('checkout-btn');
        let selectedProducts = new Set();
        let productData = {};

        tambahButtons.forEach(btn => {
            btn.addEventListener('click', function () {
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

        // Animasi gambar terbang ke bottom bar
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

        // Checkout button
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function () {
                if (selectedProducts.size === 0) {
                    alert('Pilih minimal 1 produk sebelum checkout.');
                    return;
                }
                const data = encodeURIComponent(JSON.stringify(Object.values(productData)));
                window.location.href = `/co-detail?data=${data}`;
            });
        }

        // Popup
        window.togglePopup = function () {
            const popup = document.getElementById('popup-pilih');
            if (!popup) return;
            popup.style.display = (popup.style.display === 'flex' || popup.style.display === 'block') ? 'none' : 'flex';
        };
    });

    // Modal detail produk
    const wastesData = @json($wastes);

    function openDetailModal(id){
        const w = wastesData.find(x => x.id === id);
        if(!w) return;

        let photosHtml = '';
        if(w.photo){
            photosHtml += `<img src="/storage/${w.photo}" style="max-width:100%; border-radius:8px;">`;
        } else {
            photosHtml = '<em>Tidak ada foto</em>';
        }
        document.getElementById('modalPhotos').innerHTML = photosHtml;

        document.getElementById('modalTypeName').innerText = w.type_name;
        document.getElementById('modalCategory').innerText = w.category?.category_name ?? '-';
        document.getElementById('modalStock').innerText = w.stock?.available_weight ?? 0;
        document.getElementById('modalPrice').innerText = (w.price_per_unit ?? 0).toLocaleString('id-ID');
        document.getElementById('modalTimesBought').innerText = w.times_bought ?? 0;

        const stars = Math.min(5, Math.ceil((w.times_bought || 0)/2));
        document.getElementById('modalStars').innerHTML = '★'.repeat(stars) + '☆'.repeat(5-stars);

        document.getElementById('detailModal').style.display='flex';
    }
    function closeDetailModal(){
        document.getElementById('detailModal').style.display='none';
    }
    </script>

</body>
</html>
