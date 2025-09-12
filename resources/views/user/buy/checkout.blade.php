<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}?v={{ time() }}">
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
                <p id="deliveryAddress">
                    {!! $addresses->first()->address ?? 'Alamat belum diatur' !!}
                </p>
                <p><strong>Phone:</strong> <span id="deliveryPhone">{{ $addresses->first()->phone ?? '-' }}</span></p>
                <button class="btn save change-address-btn">Ganti Alamat</button>
            </div>

            <!-- Popup Modal Ganti Alamat -->
            <div id="addressModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Pilih Alamat</h3>
                    <ul id="addressList">
                        @foreach($addresses as $addr)
                            <li data-id="{{ $addr->id }}">
                                <strong>{{ $addr->name }}</strong><br>
                                {!! $addr->address !!}<br>
                                <small>📞 {{ $addr->phone }}</small>
                            </li>
                        @endforeach
                    </ul>
                    <button class="btn cancel" onclick="toggleAddressModal(false)">Batalkan</button>
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

            {{-- Popup Ambil di Tempat --}}
            <div id="pickupModal" class="modal" style="display:none;">
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

            {{-- === Daftar Produk dari Database === --}}
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

            {{-- === Detail Produk === --}}
            <div class="product-details">
                @foreach($items as $it)
                    <div>
                        <span>{{ $it['type_name'] }} ({{ $it['quantity'] }} kg)</span>
                        <span>Rp {{ number_format($it['subtotal'],0,',','.') }}</span>
                    </div>
                @endforeach
            </div>

            {{-- === Pilihan Pembayaran === --}}
            <h2 class="section-title">Pembayaran</h2>
            <div class="payment-options">
                <img src="{{ asset('img/dana.png') }}" alt="Dana" class="payment-method" data-method="dana">
                <img src="{{ asset('img/qris.png') }}" alt="QRIS" class="payment-method" data-method="qris">
            </div>

            {{-- Popup QRIS --}}
            <div id="qrisModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
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

            <div class="saved">You saved {{ number_format($discount ?? 0,0,',','.') }} on this order</div>

            {{-- === Form Checkout === --}}
            <form action="{{ route('checkout.prepare') }}" method="POST" id="checkout-form">
                @csrf
                @foreach($items as $it)
                    <input type="hidden" name="items[{{ $it['waste_type_id'] }}][selected]" value="1">
                    <input type="hidden" name="items[{{ $it['waste_type_id'] }}][quantity]" value="{{ $it['quantity'] }}">
                @endforeach

                <input type="hidden" name="shipping_method" id="shipping_method">
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
        // === Modal Alamat ===
        const addressModal = document.getElementById("addressModal");
        const changeAddressBtn = document.querySelector(".change-address-btn");
        const closeAddressModal = addressModal.querySelector(".close");
        const addressList = document.getElementById("addressList");

        const deliveryName = document.getElementById("deliveryName");
        const deliveryAddress = document.getElementById("deliveryAddress");
        const deliveryPhone = document.getElementById("deliveryPhone");
        const addressIdInput = document.getElementById("address_id");

        // Event pilih alamat
        addressList.querySelectorAll("li").forEach(li => {
            li.addEventListener("click", function () {
                deliveryName.innerHTML = this.querySelector("strong").innerHTML;
                deliveryAddress.innerHTML = this.innerHTML.split('<br>')[1];
                deliveryPhone.textContent = this.querySelector("small").textContent.replace('📞 ', '');
                addressIdInput.value = this.dataset.id;
                toggleAddressModal(false);
            });
        });

        if (changeAddressBtn) {
            changeAddressBtn.addEventListener("click", () => toggleAddressModal(true));
        }

        closeAddressModal.addEventListener("click", () => toggleAddressModal(false));

        function toggleAddressModal(show) {
            addressModal.style.display = show ? "block" : "none";
        }
        window.toggleAddressModal = toggleAddressModal;

        // === Shipping ===
        const shippingButtons = document.querySelectorAll(".shipping-btn");
        const shippingMethodInput = document.getElementById("shipping_method");
        const pickupModal = document.getElementById("pickupModal");
        const pickupSelectBtn = pickupModal.querySelector(".btn-pilih");
        const pickupAddress = document.getElementById("pickupAddress");
        const selectedLocationDisplay = document.getElementById("selectedLocation");
        const pickupLocationInput = document.getElementById("pickup_location");

        let selectedShipping = null;
        let selectedPickupLocation = "";

        shippingButtons.forEach(btn => {
            btn.addEventListener("click", function () {
                shippingButtons.forEach(b => b.classList.remove("active"));
                this.classList.add("active");

                selectedShipping = this.dataset.type;
                shippingMethodInput.value = selectedShipping;

                if (selectedShipping === "ambil") {
                    togglePickupModal(true);
                } else {
                    selectedPickupLocation = "";
                    if (selectedLocationDisplay) selectedLocationDisplay.style.display = "none";
                }
            });
        });

        pickupSelectBtn.addEventListener("click", function () {
            if (!pickupAddress.value) {
                alert("Pilih lokasi terlebih dahulu!");
                return;
            }
            selectedPickupLocation = pickupAddress.value;
            pickupLocationInput.value = selectedPickupLocation;

            if (selectedLocationDisplay) {
                selectedLocationDisplay.textContent = `Lokasi Pengambilan: ${selectedPickupLocation}`;
                selectedLocationDisplay.style.display = "block";
            }

            togglePickupModal(false);
        });

        function togglePickupModal(show) {
            pickupModal.style.display = show ? "block" : "none";
        }
        window.togglePickupModal = togglePickupModal;

        // === Payment ===
        const paymentMethods = document.querySelectorAll(".payment-method");
        const qrisModal = document.getElementById("qrisModal");
        const qrisClose = qrisModal.querySelector(".close");
        const paymentMethodInput = document.getElementById("payment_method");

        paymentMethods.forEach(img => {
            img.addEventListener("click", function () {
                const method = this.dataset.method;
                paymentMethodInput.value = method;
                if (method === "qris") {
                    toggleQrisModal(true);
                }
            });
        });

        qrisClose.addEventListener("click", () => toggleQrisModal(false));
        function toggleQrisModal(show) {
            qrisModal.style.display = show ? "block" : "none";
        }
        window.toggleQrisModal = toggleQrisModal;
    });
    </script>

</body>
</html>
