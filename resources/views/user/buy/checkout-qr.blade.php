<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS</title>
    <link rel="stylesheet" href="{{ asset('css/qris.css') }}?v={{ time() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    {{-- Header --}}
    @include('partials.header')

    <div class="container qris-container text-center">
        <h3>Bayar dengan QRIS</h3>

        {{-- Box QR --}}
        <div id="qrcode" class="qris-box"></div>
        <noscript>Aktifkan JavaScript untuk melihat QR Code.</noscript>

        {{-- Countdown --}}
        <p id="countdown" class="countdown"></p>

        {{-- Status Transaksi --}}
        <div id="statusBox" class="status-box">
            Status: <strong id="txStatus">{{ $tx->status }}</strong>
        </div>

        {{-- Tombol aksi --}}
        <div class="btn-group">
            <a href="{{ route('buy-waste.index') }}" class="btn btn-secondary">⬅ Kembali</a>
            <button id="downloadBtn" class="btn btn-primary">⬇ Download QR</button>
        </div>
    </div>

    {{-- CDN qrcode.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // === QR Code ===
        const qrText = {!! json_encode($tx->qr_text ?: 'QRIS-DUMMY') !!};
        const qrBox = document.getElementById("qrcode");
        qrBox.innerHTML = "";

        const qr = new QRCode(qrBox, {
            text: qrText,
            width: 300,
            height: 300,
            correctLevel: QRCode.CorrectLevel.H
        });

        // === Download QR ===
        document.getElementById("downloadBtn").addEventListener("click", function () {
            const img = qrBox.querySelector("img") || qrBox.querySelector("canvas");
            if (img) {
                const link = document.createElement("a");
                link.href = img.src || img.toDataURL("image/png");
                link.download = "qris-transaction-{{ $tx->id }}.png";
                link.click();
            }
        });

        // === Countdown ===
        const expireAtMs = {{ $tx->expired_at ? ($tx->expired_at->getTimestamp() * 1000) : 'null' }};
        const statusEl = document.getElementById('txStatus');
        const countdownEl = document.getElementById('countdown');

        if (expireAtMs) {
            const timer = setInterval(() => {
                const nowMs = Date.now();
                const distance = expireAtMs - nowMs;

                if (distance <= 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "⏰ Waktu habis — transaksi otomatis dibatalkan.";
                    updateStatusOnce();
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdownEl.innerHTML = `⏳ Sisa waktu: ${minutes}m ${seconds}s`;
            }, 500);
        } else {
            countdownEl.innerHTML = "⚠️ Expired time tidak tersedia.";
        }

        // === Polling status ===
        let polling = setInterval(fetchStatus, 5000);
        let statusFetched = false;

        function fetchStatus() {
            fetch("{{ route('transactions.status',['id'=>$tx->id]) }}", {
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    statusEl.innerText = data.status;
                    updateStatusClass(data.status);
                    if (data.status === 'paid') {
                        clearInterval(polling);
                        setTimeout(() => {
                            window.location.href = "{{ route('transactions.index') }}";
                        }, 1000);
                    }
                }
            })
            .catch(err => console.error('Gagal ambil status:', err));
        }

        function updateStatusOnce() {
            if (statusFetched) return;
            statusFetched = true;
            fetchStatus();
        }

        function updateStatusClass(status) {
            const statusBox = document.getElementById('statusBox');
            statusBox.classList.remove("status-pending","status-paid","status-failed","status-expired");
            if (status === "pending") statusBox.classList.add("status-pending");
            if (status === "paid") statusBox.classList.add("status-paid");
            if (status === "failed") statusBox.classList.add("status-failed");
            if (status === "expired") statusBox.classList.add("status-expired");
        }
    </script>
</body>
</html>
