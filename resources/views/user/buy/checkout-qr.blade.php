<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS</title>
    <link rel="stylesheet" href="{{ asset('css/qris.css') }}?v={{ time() }}">
</head>
<body>
    {{-- Header --}}
    @include('partials.header')
<div class="container qris-container text-center">
    <h3>Bayar dengan QRIS</h3>

    <div id="qrcode" class="qris-box"></div>

    <p id="countdown" class="countdown"></p>

    <div id="statusBox" class="status-box">
        Status: <strong id="txStatus">{{ $tx->status }}</strong>
    </div>

    <a href="{{ route('buy-waste.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

<!-- CDN qrcode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // render QR
    new QRCode(document.getElementById("qrcode"), {
        text: {!! json_encode($tx->qr_text ?? 'QRIS-DUMMY') !!},
        width: 300,
        height: 300,
    });

    const expireAtMs = {{ $tx->expired_at ? ($tx->expired_at->timestamp * 1000) : 'null' }};
    const statusEl = document.getElementById('txStatus');
    const countdownEl = document.getElementById('countdown');

    if (expireAtMs) {
        const timer = setInterval(() => {
            const nowMs = Date.now();
            const distance = expireAtMs - nowMs;

            if (distance <= 0) {
                clearInterval(timer);
                countdownEl.innerHTML = "Waktu habis — transaksi otomatis dibatalkan.";
                fetch("{{ route('transactions.status',['id'=>$tx->id]) }}");
                return;
            }
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            countdownEl.innerHTML = `Sisa waktu: ${minutes}m ${seconds}s`;
        }, 500);
    }

    // polling status
    const poll = setInterval(function(){
        fetch("{{ route('transactions.status',['id'=>$tx->id]) }}")
          .then(res => res.json())
          .then(data => {
              if (data.status) {
                  statusEl.innerText = data.status;
                  if (data.status === 'paid') {
                      clearInterval(poll);
                      window.location.href = "{{ route('transactions.index') }}";
                  }
              }
          }).catch(err => console.error(err));
    }, 5000);
</script>
</body>
</html>
