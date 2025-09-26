<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pesanan Berhasil</title>
  <link rel="stylesheet" href="{{ asset('css/detailpay.css') }}">
</head>
<body>
  {{-- Include header --}}
    @include('partials.header')

  <!-- Konten -->
  <div class="container">
    <h1>Pesanan Berhasil</h1>
    <div class="success-icon">✔</div>

    <div class="buttons">
      <button class="payment-btn" type="button" 
                onclick="window.location.href='{{ route('belibarang') }}'">
            Kembali
        </button>
      <button class="btn btn-success" id="notifBtn">Dapatkan Notifikasi</button>
    </div>

    <!-- Produk -->
    <div class="products">
      <div class="card">
        <img src="https://i.ibb.co/2KpTwfY/coca.jpg" alt="Coca">
        <p>Lorem ipsum dolor sit amet consectetur</p>
        <span>$17,00</span>
      </div>
      <div class="card">
        <img src="https://i.ibb.co/27V3G9V/plastic.jpg" alt="Plastic">
        <p>Lorem ipsum dolor sit amet consectetur</p>
        <span>$17,00</span>
      </div>
      <div class="card">
        <img src="https://i.ibb.co/GsrJ6CR/marlboro.jpg" alt="Marlboro">
        <p>Lorem ipsum dolor sit amet consectetur</p>
        <span>$17,00</span>
      </div>
      <div class="card">
        <img src="https://i.ibb.co/s17tBkX/bottle.jpg" alt="Bottle">
        <p>Lorem ipsum dolor sit amet consectetur</p>
        <span>$17,00</span>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
  const notifBtn = document.getElementById("notifBtn");
  notifBtn.addEventListener("click", () => {
    alert("Notifikasi pesanan berhasil diaktifkan!");
  });
});

  </script>
</body>
</html>
