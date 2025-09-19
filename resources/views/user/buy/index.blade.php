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
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const cartUrl = "{{ route('checkout.cart') }}";
    const addUrl = "{{ route('checkout.add') }}";
    const removeUrl = "{{ route('checkout.remove') }}";

    const tambahButtons = document.querySelectorAll('.tambah-btn');
    const selectedCountEl = document.getElementById('selected-count');
    const checkoutBtn = document.getElementById('checkout-btn');

    // Start with empty, will fill from server (or localStorage fallback)
    let selectedProducts = {};

    function updateSelectedCount() {
        selectedCountEl.textContent = `${Object.keys(selectedProducts).length} produk dipilih`;
        localStorage.setItem('selectedProducts', JSON.stringify(selectedProducts));
    }

    function restoreUIFromSelected() {
        Object.keys(selectedProducts).forEach(productId => {
            const card = document.querySelector(`.produk-card[data-id="${productId}"]`);
            if (!card) return;
            const btn = card.querySelector('.tambah-btn');
            const sizeContainer = card.querySelector('.size-container');
            if (btn) {
                btn.textContent = 'Hapus';
                btn.style.backgroundColor = '#f44336';
            }
            if (sizeContainer) sizeContainer.style.display = 'block';
        });
        updateSelectedCount();
    }

    // Ambil server cart terlebih dahulu
    fetch(cartUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            const cart = data.cart || [];
            if (cart.length > 0) {
                cart.forEach(it => selectedProducts[it.waste_type_id] = it.quantity);
                restoreUIFromSelected();
            } else {
                // fallback ke localStorage: sinkron ke server (opsional)
                const ls = JSON.parse(localStorage.getItem('selectedProducts') || '{}');
                if (Object.keys(ls).length > 0) {
                    // kirim masing-masing ke server (non-blocking)
                    Object.keys(ls).forEach(pid => {
                        fetch(addUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({ waste_type_id: pid, quantity: ls[pid] })
                        })
                        .then(r => r.json())
                        .then(resp => {
                            if (resp.success) {
                                selectedProducts[pid] = ls[pid];
                                restoreUIFromSelected();
                            }
                        })
                        .catch(() => {});
                    });
                }
            }
        })
        .catch(() => {
            // jika fetch gagal, fallback ke localStorage saja
            const ls = JSON.parse(localStorage.getItem('selectedProducts') || '{}');
            selectedProducts = ls;
            restoreUIFromSelected();
        });

    // Event listener untuk tombol tambah/hapus
    tambahButtons.forEach(btn => {
        btn.addEventListener('click', function () {
            const card = btn.closest('.produk-card');
            const productId = card.dataset.id;
            const stok = parseInt(card.dataset.stock);
            const qty = parseInt(card.querySelector('.size-input')?.value || 1);
            const sizeContainer = card.querySelector('.size-container');

            if (stok === 0) { alert('Produk ini stoknya habis!'); return; }

            if (selectedProducts[productId]) {
                // hapus item di server
                fetch(removeUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        delete selectedProducts[productId];
                        btn.textContent = 'Tambah';
                        btn.style.backgroundColor = '';
                        if (sizeContainer) sizeContainer.style.display = 'none';
                        updateSelectedCount();
                    } else {
                        alert(resp.error || 'Gagal menghapus item.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal menghapus item (network).');
                });
            } else {
                // jika size picker belum terlihat, tampilkan dulu
                if (sizeContainer && sizeContainer.style.display === 'none') {
                    sizeContainer.style.display = 'block';
                    return;
                }

                // tambah item ke server
                fetch(addUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ waste_type_id: productId, quantity: qty })
                })
                .then(res => res.json())
                .then(resp => {
                    if (resp.success) {
                        selectedProducts[productId] = qty;
                        btn.textContent = 'Hapus';
                        btn.style.backgroundColor = '#f44336';
                        flyToCart(card.querySelector('img'));
                        updateSelectedCount();
                    } else {
                        alert(resp.error || 'Gagal menambahkan item.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Gagal menambahkan ke checkout (network).');
                });
            }
        });
    });

    // Animasi terbang ke cart (tidak berubah)
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

        setTimeout(() => imgClone.remove(), 800);
    }

    // checkout redirect
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function () {
            if (Object.keys(selectedProducts).length === 0) {
                alert('Pilih minimal 1 produk sebelum checkout.');
                return;
            }
            window.location.href = "{{ route('checkout.form') }}";
        });
    }

    // size pickers
    document.querySelectorAll('.produk-card').forEach(card => {
        const inc = card.querySelector('.increase');
        const dec = card.querySelector('.decrease');
        const sizeInput = card.querySelector('.size-input');
        if (inc && dec && sizeInput) {
            inc.addEventListener('click', () => sizeInput.value = parseInt(sizeInput.value) + 1);
            dec.addEventListener('click', () => {
                if (parseInt(sizeInput.value) > 1) sizeInput.value = parseInt(sizeInput.value) - 1;
            });
        }
    });

    // modal detail (tidak berubah)
    const wastesData = @json($wastes);

    window.openDetailModal = function(id) {
        const w = wastesData.find(x => x.id == id);
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
                    <div class="modal-bottom"><span id="modal-total">Rp ${(w.price_per_unit ?? 0).toLocaleString('id-ID')}</span></div>
                </div>
            </main>`;
        document.getElementById('modalBody').innerHTML = modalHTML;
        document.getElementById('detailModal').style.display = 'flex';
    };

    window.closeDetailModal = function() {
        document.getElementById('detailModal').style.display = 'none';
    };
});
</script>
</body>
</html>
