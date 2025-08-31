@extends('layouts.app')
@section('content')
<div class="container text-center">
    <h3>Bayar dengan QRIS</h3>

    <div class="my-3">
        {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)->generate($tx->qr_text) !!}
    </div>

    <p id="countdown"></p>

    <div id="statusBox">
        Status: <strong id="txStatus">{{ $tx->status }}</strong>
    </div>

    <a href="{{ route('buy.index') }}" class="btn btn-secondary mt-3">Kembali</a>
</div>

<script>
    let expireAt = new Date("{{ $tx->expired_at }}").getTime();
    let txId = {{ $tx->id }};
    let countdownEl = document.getElementById('countdown');
    let statusEl = document.getElementById('txStatus');

    // countdown
    let x = setInterval(function() {
        let now = new Date().getTime();
        let distance = expireAt - now;
        if (distance < 0) {
            clearInterval(x);
            countdownEl.innerHTML = "Waktu habis — menunggu konfirmasi staff (akan otomatis cancel jika tidak di-approve)";
            return;
        }
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);
        countdownEl.innerHTML = `Sisa waktu: ${minutes}m ${seconds}s`;
    }, 500);

    // polling setiap 5 detik
    setInterval(function(){
        fetch("{{ url('/transactions') }}/" + txId + "/status")
          .then(res => res.json())
          .then(data => {
              if (data.status) {
                  statusEl.innerText = data.status;
                  // kalau sudah paid atau canceling, bisa hentikan polling jika mau:
                  if (data.status === 'paid' || data.status === 'canceling') {
                      // optional: redirect atau show message
                  }
              }
          })
          .catch(err => console.error(err));
    }, 5000);
</script>
@endsection
