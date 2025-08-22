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
    <div class="checkout-container">
<h1 style="text-align:center; font-size:2rem; margin-top:20px;">Checkout</h1>
        <div class="back-btn" onclick="history.back()">&#8592;</div>

       {{-- Info Pengiriman --}}
<div class="delivery-info">
    <p>Delivery to</p>
    <h3 id="deliveryName">Faizan Khan, 344022</h3>
    <p id="deliveryAddress">
        Opp State Bank Of India,<br>
        Asotra. Barmer Dist,<br>
        Rajasthan IN
    </p>
    <p><strong>Phone:</strong> <span id="deliveryPhone">7976382557</span></p>
    <button class="change-address-btn">Ganti Alamat</button>
</div>

<!-- Popup Modal Ganti Alamat -->
<div id="addressModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Pilih Alamat</h3>
        <ul id="addressList"></ul>
        <button class="cancel-btn" onclick="toggleAddressModal(false)">Batalkan</button>
    </div>
</div>

        {{-- Pilihan Pengiriman --}}
<h2>Metode Pengiriman</h2>
<div class="shipping-options">
    <button class="shipping-btn" data-type="antar">Antar ke Alamat</button>
    <button class="shipping-btn" data-type="ambil">Ambil Barang di Tempat</button>
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
            <option value="OkGreen Jl.Budi">OkGreen Jl.Budi</option>
        </select>
        <button class="btn-pilih">Pilih</button>
        <button class="cancel-btn" onclick="togglePickupModal(false)">Batalkan</button>
    </div>
</div>

        {{-- Daftar Produk --}}
        <div class="product-list">
            <div class="product-card">
                <img src="{{ asset('img/sample2.png') }}" alt="Coca Cola">
            </div>
            <div class="product-card">
                <img src="{{ asset('img/sample3.png') }}" alt="Plastic">
            </div>
            <div class="product-card">
                <img src="{{ asset('img/sample4.png') }}" alt="Cigarette">
            </div>
        </div>

        {{-- Detail Produk --}}
        <div class="product-details">
            <div><span>used Coca Cola cans (1.5kg)</span> <span>Rp.5.000</span></div>
            <div><span>used Plastic (1.5kg)</span> <span>Rp.5.000</span></div>
            <div><span>Cigarette cardboard waste (1.5kg)</span> <span>Rp.5.000</span></div>
        </div>

        {{-- Pilihan Pembayaran (dipindah ke sini) --}}
        <h2>Pembayaran</h2>
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

        {{-- Rincian Pembayaran --}}
        <div class="payment-details">
            <div><span>Price (3 items)</span> <span>Rp.15.000</span></div>
            <div class="discount"><span>Discount</span> <span>Rp.-5.200</span></div>
            <div><span>Delivery Charges</span> <span>FREE</span></div>
            <div class="total"><span>Total Amount</span> <span>Rp.9.800</span></div>
        </div>

        <div class="saved">You saved 5.200 on this order</div>

        {{-- Tombol Buat Pesanan --}}
        <button class="payment-btn" type="button" 
                onclick="window.location.href='{{ route('detail_payment') }}'">
            Buat Pesanan
        </button>
        
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
    const dummyAddresses = [
        {
            name: "Sifa Nur Agnia, 40511",
            address: "Jl.Belakang Kejaksaan,<br>Cipageran,<br>Cimahi Utara",
            phone: "7976382557"
        },
        {
            name: "Dika Indradhy, 56001",
            address: "Jl.Budi,<br>Cilember,<br>Bandung",
            phone: "9988776655"
        },
    ];

    const addressModal = document.getElementById("addressModal");
    const addressList = document.getElementById("addressList");
    const changeAddressBtn = document.querySelector(".change-address-btn");
    const closeAddressModal = addressModal.querySelector(".close");

    const deliveryName = document.getElementById("deliveryName");
    const deliveryAddress = document.getElementById("deliveryAddress");
    const deliveryPhone = document.getElementById("deliveryPhone");

    // Render daftar alamat
    dummyAddresses.forEach(addr => {
        const li = document.createElement("li");
        li.innerHTML = `<strong>${addr.name}</strong><br>${addr.address}<br><small>📞 ${addr.phone}</small>`;
        li.addEventListener("click", function () {
            deliveryName.innerHTML = addr.name;
            deliveryAddress.innerHTML = addr.address;
            deliveryPhone.textContent = addr.phone;
            toggleAddressModal(false);
        });
        addressList.appendChild(li);
    });

    // Event buka modal alamat
    if (changeAddressBtn) {
        changeAddressBtn.addEventListener("click", function () {
            toggleAddressModal(true);
        });
    }

    // Event tutup modal alamat
    closeAddressModal.addEventListener("click", function () {
        toggleAddressModal(false);
    });

    window.addEventListener("click", function (e) {
        if (e.target === addressModal) toggleAddressModal(false);
    });

    function toggleAddressModal(show) {
        addressModal.style.display = show ? "block" : "none";
    }
    window.toggleAddressModal = toggleAddressModal;

    // --- Shipping ---
    const shippingButtons = document.querySelectorAll(".shipping-btn");
    const pickupModal = document.getElementById("pickupModal");
    const pickupClose = pickupModal.querySelector(".close");
    const pickupSelectBtn = pickupModal.querySelector(".btn-pilih");
    const pickupCancelBtn = pickupModal.querySelector(".cancel-btn");
    const pickupAddress = document.getElementById("pickupAddress");
    const selectedLocationDisplay = document.getElementById("selectedLocation");

    let selectedShipping = null;
    let selectedPickupLocation = "";

    shippingButtons.forEach(btn => {
        btn.addEventListener("click", function () {
            shippingButtons.forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            selectedShipping = this.dataset.type;

            if (selectedShipping === "ambil") {
                togglePickupModal(true);
            } else {
                selectedPickupLocation = "";
                if (selectedLocationDisplay) selectedLocationDisplay.style.display = "none";
            }
        });
    });

    // Tombol pilih lokasi pickup
    pickupSelectBtn.addEventListener("click", function () {
        if (!pickupAddress.value) {
            alert("Pilih lokasi terlebih dahulu!");
            return;
        }
        selectedPickupLocation = pickupAddress.value;

        if (selectedLocationDisplay) {
            selectedLocationDisplay.textContent = `Lokasi Pengambilan: ${selectedPickupLocation}`;
            selectedLocationDisplay.style.display = "block";
        }

        togglePickupModal(false);
    });

    // Tombol tutup (X) modal pickup
    pickupClose.addEventListener("click", function () {
        togglePickupModal(false);
    });

    // Tombol batalkan modal pickup
    pickupCancelBtn.addEventListener("click", function () {
        togglePickupModal(false);
    });

    // Tutup modal pickup kalau klik di luar
    window.addEventListener("click", function (e) {
        if (e.target === pickupModal) togglePickupModal(false);
    });

    function togglePickupModal(show) {
        pickupModal.style.display = show ? "block" : "none";
    }
    window.togglePickupModal = togglePickupModal;

    // --- Payment ---
    const paymentMethods = document.querySelectorAll(".payment-method");
    const qrisModal = document.getElementById("qrisModal");
    const qrisClose = qrisModal.querySelector(".close");

    paymentMethods.forEach(img => {
        img.addEventListener("click", function () {
            const method = this.dataset.method;
            if (method === "dana") {
                window.location.href = "https://link.dana.id/minta-bayar";
            } else if (method === "qris") {
                toggleQrisModal(true);
            }
        });
    });

    qrisClose.addEventListener("click", function () {
        toggleQrisModal(false);
    });

    window.addEventListener("click", function (e) {
        if (e.target === qrisModal) toggleQrisModal(false);
    });

    function toggleQrisModal(show) {
        qrisModal.style.display = show ? "block" : "none";
    }
    window.toggleQrisModal = toggleQrisModal;

    // --- Proses Pembayaran ---
    window.processPayment = function () {
        if (!selectedShipping) {
            alert("Pilih metode pengiriman terlebih dahulu!");
            return;
        }
        if (selectedShipping === "ambil" && !selectedPickupLocation) {
            alert("Pilih lokasi pengambilan terlebih dahulu!");
            return;
        }
        // lanjut ke pembayaran
        window.location.href = "{{ url('/payment') }}";
    };
});
</script>

</body>
</html>
