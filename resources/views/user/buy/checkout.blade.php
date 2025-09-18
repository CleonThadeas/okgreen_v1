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

            {{-- Modal Pilih Alamat --}}
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
                    <button class="btn save" onclick="toggleAddAddressModal(true)">+ Tambah Alamat Baru</button>
                    <button class="btn cancel" onclick="toggleAddressModal(false)">Batalkan</button>
                </div>
            </div>

            {{-- Modal Tambah Alamat --}}
            <div id="addAddressModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="toggleAddAddressModal(false)">&times;</span>
                    <h3>Tambah Alamat Baru</h3>
                    <form id="addAddressForm">
                        @csrf
                        <div class="form-group">
                            <label for="addressName">Nama Penerima</label>
                            <input type="text" id="addressName" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="addressPhone">Nomor Telepon</label>
                            <input type="text" id="addressPhone" name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="addressDetail">Alamat Lengkap</label>
                            <textarea id="addressDetail" name="address" required></textarea>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn save">Simpan</button>
                            <button type="button" class="btn cancel" onclick="toggleAddAddressModal(false)">Batal</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- === Metode Pengiriman === --}}
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

            {{-- === Rincian Harga === --}}
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

            {{-- === Metode Pembayaran === --}}
            <h2 class="section-title">Metode Pembayaran</h2>
            <div class="payment-options">
                <img src="{{ asset('img/dana.png') }}" alt="Dana" class="payment-method" data-method="dana">
                <img src="{{ asset('img/qris.png') }}" alt="QRIS" class="payment-method" data-method="qris">
            </div>

            {{-- Popup QRIS --}}
            <div id="qrisModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="toggleQrisModal(false)">&times;</span>
                    <h3>Scan QRIS untuk Membayar</h3>
                    <img src="{{ asset('img/Qris-Dummy.jpg') }}" alt="QRIS Code" style="width:250px;">
                </div>
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
        // public/js/checkout.js

document.addEventListener("DOMContentLoaded", () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // === Modal Alamat ===
    window.toggleAddressModal = (show = true) => {
        document.getElementById("addressModal").style.display = show ? "block" : "none";
    };

    window.toggleAddAddressModal = (show = true) => {
        document.getElementById("addAddressModal").style.display = show ? "block" : "none";
    };

    // Pilih alamat
    document.querySelectorAll(".address-item").forEach(item => {
        item.addEventListener("click", () => {
            const id = item.dataset.id;
            const name = item.querySelector("strong").textContent;
            const address = item.innerHTML.split("<br>")[1];
            const phone = item.querySelector("small").textContent;

            document.getElementById("deliveryName").textContent = name;
            document.getElementById("deliveryAddress").innerHTML = address;
            document.getElementById("deliveryPhone").textContent = phone.replace("📞 ","");
            document.getElementById("address_id").value = id;
            document.getElementById("address_type").value = "antar";

            toggleAddressModal(false);
        });
    });

    // Tambah alamat baru (AJAX)
    const addForm = document.getElementById("addAddressForm");
    if (addForm) {
        addForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = new FormData(addForm);

            const res = await fetch("/checkout/address", {
                method: "POST",
                headers: { "X-CSRF-TOKEN": csrfToken },
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                alert("Alamat berhasil ditambahkan");
                location.reload();
            } else {
                alert("Gagal menambah alamat");
            }
        });
    }

    // === Pengiriman ===
    document.querySelectorAll(".shipping-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            if (btn.dataset.type === "antar") {
                document.getElementById("address_type").value = "antar";
                document.getElementById("selectedLocation").style.display = "none";
            } else {
                document.getElementById("address_type").value = "ambil";
                togglePickupModal(true);
            }
        });
    });

    // Modal pickup
    window.togglePickupModal = (show = true) => {
        document.getElementById("pickupModal").style.display = show ? "block" : "none";
    };

    document.querySelector("#pickupModal .btn-pilih").addEventListener("click", () => {
        const lokasi = document.getElementById("pickupAddress").value;
        if (lokasi) {
            document.getElementById("pickup_location").value = lokasi;
            document.getElementById("selectedLocation").style.display = "block";
            document.getElementById("selectedLocation").textContent = "📍 " + lokasi;
            togglePickupModal(false);
        }
    });

    // === Metode Pembayaran ===
    document.querySelectorAll(".payment-method").forEach(pm => {
        pm.addEventListener("click", () => {
            const method = pm.dataset.method;
            document.getElementById("payment_method").value = method;

            if (method === "qris") toggleQrisModal(true);
            else toggleQrisModal(false);
        });
    });

    window.toggleQrisModal = (show = true) => {
        document.getElementById("qrisModal").style.display = show ? "block" : "none";
    };
});
    </script>
</body>
</html>
