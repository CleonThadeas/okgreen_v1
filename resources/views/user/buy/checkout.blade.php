<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .shipping-btn.active,
        .payment-method.active {
            border: 2px solid #28a745;
            background-color: #e6ffee;
            color: #28a745;
        }
        .payment-method {
            cursor: pointer;
            border: 2px solid transparent;
            padding: 5px;
            transition: all 0.3s;
            max-width: 120px;
        }
        .shipping-btn {
            margin: 5px;
            transition: background-color 0.3s, border 0.3s;
        }
    </style>
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
                <h3 id="deliveryName">{{ optional($addresses->first())->name ?? 'Nama Penerima' }}</h3>
                <p id="deliveryAddress">{!! optional($addresses->first())->address ?? 'Alamat belum diatur' !!}</p>
                <p><strong>Phone:</strong> <span id="deliveryPhone">{{ optional($addresses->first())->phone ?? '-' }}</span></p>
                <button class="btn save change-address-btn" onclick="toggleAddressModal(true)">Ganti Alamat</button>
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
                <button type="button" class="shipping-btn btn save" data-type="antar">Antar ke Alamat</button>
                <button type="button" class="shipping-btn btn save" data-type="pickup">Ambil Barang di Tempat</button>
            </div>
            <p id="selectedLocation" style="display:none; margin-top:10px;"></p>
            <hr>

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
                    <span id="basePrice">Rp {{ number_format($subtotal,0,',','.') }}</span>
                </div>
                <div class="discount">
                    <span>Discount</span>
                    <span id="discountAmount">Rp -{{ number_format($discount ?? 0,0,',','.') }}</span>
                </div>
                <div>
                    <span>Delivery Charges</span>
                    <span id="deliveryCharge">{{ $shipping == 0 ? 'FREE' : 'Rp '.number_format($shipping,0,',','.') }}</span>
                </div>
                <div class="total">
                    <span>Total Amount</span>
                    <span id="totalAmount">Rp {{ number_format($total,0,',','.') }}</span>
                </div>
            </div>

            {{-- === Metode Pembayaran === --}}
            <h2 class="section-title">Metode Pembayaran</h2>
            <div class="payment-options">
                <img src="{{ asset('img/qris.png') }}" alt="QRIS" class="payment-method" data-method="qris">
            </div>

            {{-- === Form Checkout === --}}
            <form action="{{ route('checkout.qr', ['id' => 1]) }}" method="GET" id="checkout-form">
                @foreach($items as $it)
                    <input type="hidden" name="items[{{ $it['waste_type_id'] }}][selected]" value="1">
                    <input type="hidden" name="items[{{ $it['waste_type_id'] }}][quantity]" value="{{ $it['quantity'] }}">
                @endforeach
                <input type="hidden" name="address_type" id="address_type" value="pickup">
                <input type="hidden" name="pickup_location" id="pickup_location" value="OKGREEN Office - Jl. Lingkungan Hijau No.88, Bandung">
                <input type="hidden" name="address_id" id="address_id" value="">
                <div class="form-actions">
                    <button type="submit" class="btn save">Buat Pesanan</button>
                </div>
            </form>
        </div>
    </div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    window.toggleAddressModal = (show = true) => {
        document.getElementById("addressModal").style.display = show ? "flex" : "none";
    };
    window.toggleAddAddressModal = (show = true) => {
        document.getElementById("addAddressModal").style.display = show ? "flex" : "none";
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
            document.getElementById("address_type").value = "delivery";
            toggleAddressModal(false);
        });
    });

    // Tambah alamat
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
            if (data.success) location.reload();
            else alert("Gagal menambah alamat");
        });
    }

    // Pilih metode pengiriman
    document.querySelectorAll(".shipping-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            document.querySelectorAll(".shipping-btn").forEach(b => b.classList.remove("active"));
            btn.classList.add("active");

            if (btn.dataset.type === "antar") {
                document.getElementById("address_type").value = "delivery";
                document.getElementById("selectedLocation").style.display = "none";
                document.getElementById("pickup_location").value = "";
            } else {
                document.getElementById("address_type").value = "pickup";
                const lokasi = "OKGREEN Office - Jl. Lingkungan Hijau No.88, Bandung";
                document.getElementById("pickup_location").value = lokasi;
                document.getElementById("selectedLocation").style.display = "block";
                document.getElementById("selectedLocation").textContent = "📍 " + lokasi;
            }
        });
    });

    // Pilih metode pembayaran
    document.querySelectorAll(".payment-method").forEach(pm => {
        pm.addEventListener("click", () => {
            document.querySelectorAll(".payment-method").forEach(p => p.classList.remove("active"));
            pm.classList.add("active");
            document.getElementById("payment_method").value = pm.dataset.method;
        });
    });

  // Validasi form
document.getElementById("checkout-form").addEventListener("submit", (e) => {
    if (!document.getElementById("payment_method").value) {
        alert("Harap pilih metode pembayaran.");
        e.preventDefault();
    }
});

    // --- Variabel harga dari controller ---
let basePrice   = {{ $subtotal }};
let discount    = {{ $discount ?? 0 }};
let shipPickup  = {{ $shippingPickup }};
let shipDelivery= {{ $shippingDelivery }};
let shipping    = {{ $shipping }};

// Update total
function updateTotal() {
    let total = basePrice - discount + shipping;
    document.getElementById("deliveryCharge").textContent = 
        shipping === 0 ? "FREE" : "Rp " + shipping.toLocaleString("id-ID");
    document.getElementById("totalAmount").textContent = "Rp " + total.toLocaleString("id-ID");
}

// Pilih metode pengiriman
document.querySelectorAll(".shipping-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".shipping-btn").forEach(b => b.classList.remove("active"));
        btn.classList.add("active");

        if (btn.dataset.type === "antar") {
            document.getElementById("address_type").value = "delivery";
            document.getElementById("selectedLocation").style.display = "none";
            document.getElementById("pickup_location").value = "";
            shipping = shipDelivery; // pakai ongkir dari controller
        } else {
            document.getElementById("address_type").value = "pickup";
            const lokasi = "OKGREEN Office - Jl. Lingkungan Hijau No.88, Bandung";
            document.getElementById("pickup_location").value = lokasi;
            document.getElementById("selectedLocation").style.display = "block";
            document.getElementById("selectedLocation").textContent = "📍 " + lokasi;
            shipping = shipPickup; // pakai ongkir pickup (biasanya 0)
        }
        updateTotal();
    });
});

// Inisialisasi total pertama kali
updateTotal();

});
</script>
</body>
</html>
