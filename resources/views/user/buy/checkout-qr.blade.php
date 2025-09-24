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

        {{-- Countdown Expired --}}
        <p id="countdown" class="countdown"></p>

        {{-- Status Transaksi --}}
        <div id="statusBox" class="status-box">
            Status: <strong id="txStatus">{{ $tx->status }}</strong>
        </div>

        {{-- Tombol kembali --}}
        <a href="{{ route('buy-waste.index') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>

    {{-- CDN qrcode.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
            integrity="sha512-3Nn6y7e7Fo0zm7IuAvV38DSHLVYDBlnJrped1IovnHgwlHGawEq+y3OCAXoTr4Wr9PXgC7ngRl6VwBEm5fE1og=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // Render QR dari text transaksi
        new QRCode(document.getElementById("qrcode"), {
            text: {!! json_encode($tx->qr_text ?? 'QRIS-DUMMY') !!},
            width: 300,
            height: 300,
        });

        const expireAtMs = {{ $tx->expired_at ? ($tx->expired_at->timestamp * 1000) : 'null' }};
        const statusEl = document.getElementById('txStatus');
        const countdownEl = document.getElementById('countdown');

        // Hitung mundur waktu expired
        if (expireAtMs) {
            const timer = setInterval(() => {
                const nowMs = Date.now();
                const distance = expireAtMs - nowMs;

                if (distance <= 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "Waktu habis — transaksi otomatis dibatalkan.";
                    updateStatusOnce();
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                countdownEl.innerHTML = `Sisa waktu: ${minutes}m ${seconds}s`;
            }, 500);
        }

        // Polling status transaksi tiap 5 detik
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
                    if (data.status === 'paid') {
                        clearInterval(polling);
                        window.location.href = "{{ route('transactions.index') }}";
                    }
                }
            })
            .catch(err => console.error('Gagal ambil status:', err));
        }

        // Fungsi sekali update (untuk expired)
        function updateStatusOnce() {
            if (statusFetched) return;
            statusFetched = true;
            fetchStatus();
        }
    </script>
</body>
</html>
