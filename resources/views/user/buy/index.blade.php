{{-- resources/views/user/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Barang</title>
    <link rel="stylesheet" href="{{ asset('css/belibarang.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                     data-photo="{{ !empty($waste->photo) ? asset('storage/'.$waste->photo) : asset('img/no-image.png') }}"
                     data-deskripsi="{{ $waste->description ?? '' }}">

                    <!-- Klik gambar buka modal detail -->
                    <div class="produk-img-container" onclick="openDetailModal({{ $waste->id }})">
                        @if(!empty($waste->photo))
                            <img src="{{ asset('storage/'.$waste->photo) }}" alt="{{ $waste->type_name }}">
                        @else
                            <img src="{{ asset('img/no-image.png') }}" alt="No Image">
                        @endif
                        @if($stok == 0)
                            <span class="stok-habis-label">Stok Habis</span>
                        @endif
                    </div>

                    <div class="produk-desc">{{ $waste->type_name }}</div>
                    <div class="produk-price">Rp {{ number_format($price, 0, ',', '.') }}</div>
                    <div class="produk-stock">Stok: {{ $stok }}</div>

                    @if($stok > 0)
                    <!-- Size Picker: Awalnya disembunyikan -->
                    <div class="size-container" style="display: none;">
                        <p class="size-label">Masukkan berat dalam <strong>Kg</strong>:</p>
                        <div class="qty-control">
                            <button type="button" class="qty-btn decrease">-</button>
                            <input type="number" class="size-input" value="1" min="1" readonly>
                            <button type="button" class="qty-btn increase">+</button>
                        </div>
                    </div>
                    @endif
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
<div id="detailModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeDetailModal()">&times;</span>
    <div id="modalBody"></div>
  </div>
</div>

    

   <script>
document.addEventListener('DOMContentLoaded', function () {
    const tambahButtons = document.querySelectorAll('.tambah-btn');
    const selectedCountEl = document.getElementById('selected-count');
    const checkoutBtn = document.getElementById('checkout-btn');
    let selectedProducts = {}; // key: productId, value: qty

    // Handle tambah/hapus produk
    tambahButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const card = btn.closest('.produk-card');
            const productId = card.dataset.id;
            const productName = card.dataset.name;
            const productImg = card.querySelector('img').src;
            const productDesc = card.dataset.deskripsi || '';
            const stok = parseInt(card.dataset.stock);
            const qty = parseInt(card.querySelector('.size-input')?.value || 1);
            const sizeContainer = card.querySelector('.size-container');

            if (stok === 0) {
                alert('Produk ini stoknya habis!');
                return;
            }

            if (selectedProducts[productId]) {
                // === Hapus produk dari session
                fetch('/checkout/remove', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                }).finally(() => {
                    delete selectedProducts[productId];
                    btn.textContent = 'Tambah';
                    btn.style.backgroundColor = '';
                    if (sizeContainer) sizeContainer.style.display = 'none';
                    updateSelectedCount();
                });
            } else {
                // Tampilkan size picker dulu
                if (sizeContainer.style.display === 'none') {
                    sizeContainer.style.display = 'block';
                    return;
                }

                // Kirim ke session Laravel
                const formData = new FormData();
                formData.append('product_id', productId);
                formData.append('size', qty);
                formData.append('qty', qty);

                fetch('/checkout/prepare', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) { alert(data.error); return; }
                    selectedProducts[productId] = qty;
                    btn.textContent = 'Hapus';
                    btn.style.backgroundColor = '#f44336';
                    flyToCart(card.querySelector('img'));
                    updateSelectedCount();
                })
                .catch(err => { console.error(err); alert('Gagal menambahkan ke checkout'); });
            }
        });
    });

    function updateSelectedCount() {
        selectedCountEl.textContent = `${Object.keys(selectedProducts).length} produk dipilih`;
    }

    // Animasi gambar terbang ke bottom bar
    function flyToCart(imgElement) {
        if (!imgElement) return;
        const bottomBar = document.getElementById('bottom-bar');
        const imgClone = imgElement.cloneNode(true);
        const rect = imgElement.getBoundingClientRect();
        const barRect = bottomBar.getBoundingClientRect();

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
            if (Object.keys(selectedProducts).length === 0) {
                alert('Pilih minimal 1 produk sebelum checkout.');
                return;
            }
            // Session Laravel sudah terisi, cukup redirect
            window.location.href = "{{ route('checkout.form') }}";
        });
    }

    // Popup Tutorial
    window.togglePopup = function () {
        const popup = document.getElementById('popup-pilih');
        if (!popup) return;
        popup.style.display = (popup.style.display === 'flex' || popup.style.display === 'block') ? 'none' : 'flex';
    };

    // Handle size picker di card
    document.querySelectorAll('.produk-card').forEach(card => {
        const increaseBtn = card.querySelector('.increase');
        const decreaseBtn = card.querySelector('.decrease');
        const sizeInput = card.querySelector('.size-input');

        if (increaseBtn && decreaseBtn && sizeInput) {
            increaseBtn.addEventListener('click', () => {
                sizeInput.value = parseInt(sizeInput.value) + 1;
            });
            decreaseBtn.addEventListener('click', () => {
                if (parseInt(sizeInput.value) > 1) {
                    sizeInput.value = parseInt(sizeInput.value) - 1;
                }
            });
        }
    });

    // === Modal Detail Produk ===
    const wastesData = @json($wastes);

    window.openDetailModal = function(id) {
        const w = wastesData.find(x => x.id === id);
        if(!w) return;

        const modalHTML = `
            <main class="detail-produk-container">
                <div class="produk-gambar">
                    <img src="${w.photo ? '/storage/' + w.photo : '/img/no-image.png'}" alt="${w.type_name}">
                </div>
                <div class="produk-info">
                    <h1>${w.type_name}</h1>
                    <p><strong>Kategori:</strong> ${w.category?.category_name ?? '-'}</p>
                    <p><strong>Stok Tersedia:</strong> ${w.stock?.available_weight ?? 0} Kg</p>
                    <p class="produk-deskripsi"><strong>Deskripsi:</strong> ${w.description ?? 'Tidak ada deskripsi'}</p>
                    <p><strong>Harga:</strong> Rp ${(w.price_per_unit ?? 0).toLocaleString('id-ID')} /Kg</p>

                    <div class="size-container">
                        <label>Masukkan Berat (Kg):</label>
                        <div class="qty-control">
                            <button type="button" class="qty-btn" id="modal-decrease">-</button>
                            <input type="number" id="modal-size" value="1" min="1" readonly>
                            <button type="button" class="qty-btn" id="modal-increase">+</button>
                        </div>
                    </div>

                    <div class="modal-bottom">
                    <span id="modal-total">Rp ${(w.price_per_unit ?? 0).toLocaleString('id-ID')}</span>
                </div>
                </div>
            </main>
        `;
        document.getElementById('modalBody').innerHTML = modalHTML;

        const modalSizeInput = document.getElementById('modal-size');
        const modalIncrease = document.getElementById('modal-increase');
        const modalDecrease = document.getElementById('modal-decrease');

        modalIncrease.addEventListener('click', () => {
            modalSizeInput.value = parseInt(modalSizeInput.value) + 1;
            updateModalTotal(w.price_per_unit);
        });
        modalDecrease.addEventListener('click', () => {
            if (parseInt(modalSizeInput.value) > 1) {
                modalSizeInput.value = parseInt(modalSizeInput.value) - 1;
                updateModalTotal(w.price_per_unit);
            }
        });

        document.getElementById('detailModal').style.display = 'flex';
    };

    function updateModalTotal(basePrice) {
        const qty = parseInt(document.getElementById('modal-size').value);
        const total = basePrice * qty;
        document.getElementById('modal-total').textContent = "Rp " + total.toLocaleString('id-ID');
    }

    window.closeDetailModal = function() {
        document.getElementById('detailModal').style.display = 'none';
    };

    window.addToCart = function(id){
    };
});
</script>
</body>
</html>
