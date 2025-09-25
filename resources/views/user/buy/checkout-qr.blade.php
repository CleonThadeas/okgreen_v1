@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h3>Bayar dengan QRIS</h3>

    <div id="qrcode" class="my-3"></div>

    <p id="countdown"></p>

    <div id="statusBox">
        Status: <strong id="txStatus">{{ $tx->status }}</strong>
    </div>

    <a href="{{ route('buy-waste.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

<!-- CDN qrcode.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    // render QR dari payload dummy
    new QRCode(document.getElementById("qrcode"), {
        text: {!! json_encode($tx->qr_text ?? 'QRIS-DUMMY') !!},
        width: 300,
        height: 300,
    });

    // --- gunakan epoch (ms) agar aman dari timezone ---
    const expireAtMs = {{ $tx->expired_at ? ($tx->expired_at->timestamp * 1000) : 'null' }};
    const txId = {{ $tx->id }};
    const countdownEl = document.getElementById('countdown');
    const statusEl = document.getElementById('txStatus');

    if (expireAtMs) {
        const timer = setInterval(() => {
            const nowMs = Date.now();
            const distance = expireAtMs - nowMs;

            if (distance <= 0) {
                clearInterval(timer);
                countdownEl.innerHTML = "Waktu habis — transaksi otomatis dibatalkan.";
                // panggil status sekali untuk memicu auto-cancel di server
                fetch("{{ route('transactions.status',['id'=>$tx->id]) }}");
                return;
            }
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            countdownEl.innerHTML = `Sisa waktu: ${minutes}m ${seconds}s`;
        }, 500);
    }

    // Polling status setiap 5 detik
    const poll = setInterval(function(){
        fetch("{{ route('transactions.status',['id'=>$tx->id]) }}")
          .then(res => res.json())
          .then(data => {
              if (data.status) {
                  statusEl.innerText = data.status;
                  if (data.status === 'paid') {
                      clearInterval(poll);
                      // redirect ke riwayat transaksi (BUKAN transactions.show!)
                      window.location.href = "{{ route('transactions.index') }}";
                  }
              }
          }).catch(err => console.error(err));
    }, 5000);
</script>
@endsection
