<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

    <div class="container">
        <div class="content">
            <h1 style="text-align:center;">Checkout</h1>
            <div class="back-btn" onclick="history.back()">&#8592;</div>

            {{-- === Info Pengiriman === --}}
            <div class="delivery-info">
                <p>Delivery to</p>
                <h3 id="deliveryName">{{ $addresses->first()->name ?? 'Nama Penerima' }}</h3>
                <p id="deliveryAddress">{!! $addresses->first()->address ?? 'Alamat belum diatur' !!}</p>
                <p><strong>Phone:</strong> <span id="deliveryPhone">{{ $addresses->first()->phone ?? '-' }}</span></p>
                <button class="btn save change-address-btn">Ganti Alamat</button>
            </div>

            <!-- Popup Modal Pilih Alamat -->
            <div id="addressModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="toggleAddressModal(false)">&times;</span>
                    <h3>Pilih Alamat</h3>
                    <ul id="addressList">
                        @foreach($addresses as $addr)
                            <li data-id="{{ $addr->id }}" class="address-item">
                                <strong>{{ $addr->name }}</strong><br>
                                {!! $addr->address !!}<br>
                                <small>📞 {{ $addr->phone }}</small>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Tombol buka tambah alamat -->
                    <button class="btn save" style="margin-top:10px;" onclick="toggleAddAddressModal(true)">
                        + Tambah Alamat Baru
                    </button>

                    <button class="btn cancel" onclick="toggleAddressModal(false)">Batalkan</button>
                </div>
            </div>

            <!-- Modal Tambah Alamat -->
            <div id="addAddressModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="toggleAddAddressModal(false)">&times;</span>
                    <h3>Tambah Alamat Baru</h3>
                    <form id="addAddressForm">
                        @csrf
                        <div class="form-group">
                            <label for="addressName">Nama Penerima</label>
                            <input type="text" id="addressName" name="name" placeholder="Nama penerima" required>
                        </div>
                        <div class="form-group">
                            <label for="addressPhone">Nomor Telepon</label>
                            <input type="text" id="addressPhone" name="phone" placeholder="08xxxx" required>
                        </div>
                        <div class="form-group">
                            <label for="addressDetail">Alamat Lengkap</label>
                            <textarea id="addressDetail" name="address" placeholder="Alamat lengkap anda" required></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn save">Simpan</button>
                            <button type="button" class="btn cancel" onclick="toggleAddAddressModal(false)">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- === Pilihan Pengiriman === --}}
            <h2 class="section-title">Metode Pengiriman</h2>
            <div class="shipping-options">
                <button class="shipping-btn btn save" data-type="antar">Antar ke Alamat</button>
                <button class="shipping-btn btn save" data-type="ambil">Ambil Barang di Tempat</button>
            </div>
            <p id="selectedLocation" style="display:none; margin-top:10px;"></p>
            <hr>

            {{-- Modal Pilih Lokasi Pickup --}}
            <div id="pickupModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="togglePickupModal(false)">&times;</span>
                    <h3>Pilih Lokasi Pengambilan</h3>
                    <select id="pickupAddress">
                        <option value="">Pilih Lokasi</option>
                        @foreach($pickupLocations as $loc)
                            <option value="{{ $loc['name'] }} - {{ $loc['address'] }}">
                                {{ $loc['name'] }} - {{ $loc['address'] }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn save btn-pilih">Pilih</button>
                    <button class="btn cancel" onclick="togglePickupModal(false)">Batalkan</button>
                </div>
            </div>

            {{-- === Daftar Produk === --}}
            <div class="product-list">
                @foreach($items as $it)
                    <div class="product-card">
                        @if(!empty($it['image']))
                            <img src="{{ asset('storage/'.$it['image']) }}" alt="{{ $it['type_name'] }}">
                        @else
                            <img src="{{ asset('img/no-image.png') }}" alt="{{ $it['type_name'] }}">
                        @endif
                        <p>{{ $it['type_name'] }} ({{ $it['quantity'] }} kg)</p>
                        <strong>Rp {{ number_format($it['subtotal'],0,',','.') }}</strong>
                    </div>
                @endforeach
            </div>

            {{-- === Detail Harga === --}}
            <div class="product-details">
                @foreach($items as $it)
                    <div>
                        <span>{{ $it['type_name'] }} ({{ $it['quantity'] }} kg)</span>
                        <span>Rp {{ number_format($it['subtotal'],0,',','.') }}</span>
                    </div>
                @endforeach
            </div>

            {{-- === Pembayaran === --}}
            <h2 class="section-title">Pembayaran</h2>
            <div class="payment-options">
                <img src="{{ asset('img/dana.png') }}" alt="Dana" class="payment-method" data-method="dana">
                <img src="{{ asset('img/qris.png') }}" alt="QRIS" class="payment-method" data-method="qris">
            </div>

            <!-- Popup QRIS -->
            <div id="qrisModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="toggleQrisModal(false)">&times;</span>
                    <h3>Scan QRIS untuk Membayar</h3>
                    <img src="{{ asset('img/Qris-Dummy.jpg') }}" alt="QRIS Code" style="width:250px;">
                </div>
            </div>

            {{-- === Rincian Pembayaran === --}}
            <div class="payment-details">
                <div>
                    <span>Price ({{ count($items) }} items)</span>
                    <span>Rp {{ number_format($subtotal,0,',','.') }}</span>
                </div>
                <div class="discount">
                    <span>Discount</span>
                    <span>Rp -{{ number_format($discount ?? 0,0,',','.') }}</span>
                </div>
                <div>
                    <span>Delivery Charges</span>
                    <span>{{ $shipping == 0 ? 'FREE' : 'Rp '.number_format($shipping,0,',','.') }}</span>
                </div>
                <div class="total">
                    <span>Total Amount</span>
                    <span>Rp {{ number_format($total,0,',','.') }}</span>
                </div>
            </div>

            <div class="saved">
                You saved {{ number_format($discount ?? 0,0,',','.') }} on this order
            </div>

            {{-- === Form Checkout === --}}
           <form action="{{ route('cart.checkout') }}" method="POST" id="checkout-form">
                @csrf
                @foreach($items as $it)
                    <input type="hidden" name="items[{{ $it['waste_type_id'] }}][selected]" value="1">
                    <input type="hidden" name="items[{{ $it['waste_type_id'] }}][quantity]" value="{{ $it['quantity'] }}">
                @endforeach
                <input type="hidden" name="address_type" id="address_type">
                <input type="hidden" name="pickup_location" id="pickup_location">
                <input type="hidden" name="payment_method" id="payment_method">
                <input type="hidden" name="address_id" id="address_id" value="{{ $addresses->first()->id ?? '' }}">

                <div class="form-actions">
                    <button type="submit" class="btn save">Buat Pesanan</button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // === Modal Pilih Alamat ===
    const addressModal = document.getElementById("addressModal");
    const changeAddressBtn = document.querySelector(".change-address-btn");
    const addressIdInput = document.getElementById("address_id");
    const deliveryName = document.getElementById("deliveryName");
    const deliveryAddress = document.getElementById("deliveryAddress");
    const deliveryPhone = document.getElementById("deliveryPhone");

    // Open modal pilih alamat
    changeAddressBtn.addEventListener("click", () => toggleAddressModal(true));

    function toggleAddressModal(show) {
        addressModal.style.display = show ? "flex" : "none";
    }
    window.toggleAddressModal = toggleAddressModal;

    // Pilih alamat yang sudah ada
    document.querySelectorAll(".address-item").forEach(li => {
        li.addEventListener("click", function () {
            deliveryName.textContent = this.querySelector("strong").textContent;
            deliveryAddress.innerHTML = this.innerHTML.split('<br>')[1];
            deliveryPhone.textContent = this.querySelector("small").textContent.replace('📞 ', '');
            addressIdInput.value = this.dataset.id;
            toggleAddressModal(false);
        });
    });

    // === Modal Tambah Alamat ===
    function toggleAddAddressModal(show) {
        document.getElementById('addAddressModal').style.display = show ? 'flex' : 'none';
    }
    window.toggleAddAddressModal = toggleAddAddressModal;

    // Submit alamat baru
    document.getElementById('addAddressForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('{{ route("address.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert('Alamat berhasil ditambahkan!');
                const newAddress = `
                    <li class="address-item" data-id="${data.address.id}">
                        <strong>${data.address.name}</strong><br>
                        ${data.address.address}<br>
                        <small>📞 ${data.address.phone}</small>
                    </li>
                `;
                document.getElementById('addressList').insertAdjacentHTML('beforeend', newAddress);
                toggleAddAddressModal(false);
                this.reset();
            } else {
                alert(data.message || 'Gagal menambahkan alamat');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menambahkan alamat.');
        });
    });

    // === Shipping ===
    const shippingButtons = document.querySelectorAll(".shipping-btn");
    const shippingMethodInput = document.getElementById("address_type");
    const pickupModal = document.getElementById("pickupModal");
    const pickupSelectBtn = pickupModal.querySelector(".btn-pilih");
    const pickupAddress = document.getElementById("pickupAddress");
    const selectedLocationDisplay = document.getElementById("selectedLocation");
    const pickupLocationInput = document.getElementById("pickup_location");

    shippingButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            shippingButtons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            if (this.dataset.type === "ambil") {
                shippingMethodInput.value = "pickup";
                togglePickupModal(true);
            } else {
                shippingMethodInput.value = "delivery";
                selectedLocationDisplay.style.display = "none";
            }
        });
    });

    pickupSelectBtn.addEventListener("click", function () {
        if (!pickupAddress.value) {
            alert("Pilih lokasi terlebih dahulu!");
            return;
        }
        pickupLocationInput.value = pickupAddress.value;
        selectedLocationDisplay.textContent = "Lokasi Pengambilan: " + pickupAddress.value;
        selectedLocationDisplay.style.display = "block";
        togglePickupModal(false);
    });

    function togglePickupModal(show) {
        pickupModal.style.display = show ? "flex" : "none";
    }
    window.togglePickupModal = togglePickupModal;

    // === Payment ===
    const paymentMethods = document.querySelectorAll(".payment-method");
    const paymentMethodInput = document.getElementById("payment_method");

    paymentMethods.forEach(img => {
        img.addEventListener("click", function () {
            paymentMethodInput.value = this.dataset.method;
            if (this.dataset.method === "qris") {
                toggleQrisModal(true);
            }
        });
    });

    function toggleQrisModal(show) {
        document.getElementById('qrisModal').style.display = show ? 'flex' : 'none';
    }
    window.toggleQrisModal = toggleQrisModal;

    // Efek klik tombol shipping dan payment
document.querySelectorAll(".shipping-btn, .payment-method").forEach(el => {
    el.addEventListener("click", () => {
        el.style.transform = "scale(0.95)";
        setTimeout(() => {
            el.style.transform = "scale(1)";
        }, 150);
    });
});

// Animasi masuk konten ketika scroll
const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if(entry.isIntersecting){
            entry.target.classList.add("animate-visible");
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll(".product-card, .payment-details, .section-title").forEach(el => {
    el.classList.add("animate-hidden");
    observer.observe(el);
});

});
</script>
</body>
</html>
