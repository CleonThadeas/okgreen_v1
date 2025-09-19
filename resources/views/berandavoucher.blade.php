<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voucher</title>
    <link rel="stylesheet" href="{{ asset('css/beranda-voucher.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <body class="dashboard-page">
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <button class="back-btn"><a href="{{ route('stoksampah') }}">←</a></button>
            <h2>Voucher</h2>
            <input type="text" placeholder="Cari">
    </div>

  <div class="points-container">
    <div class="points-box">
      <p class="points-title">Pointku saat ini</p>
      <h1 class="points-value">0</h1>
      <p class="points-subtitle">Belanjakan poin untuk mendapatkan diskon</p>
    </div>
  </div>

 <div class="cards-container">
  <div class="card">
    <img src="https://upload.wikimedia.org/wikipedia/commons/1/19/Spotify_logo_without_text.svg" alt="Spotify" class="card-logo">
    <h3>Spotify Premium</h3>
    <p>Nikmati musik tanpa iklan dengan Spotify Premium dan dengarkan lagu favoritmu offline.</p>
    <a href="{{ route('tukarvoucher') }}"><span class="card-points">3000 points</span></a>
  </div>

  <div class="card">
     <img src="{{ asset("img/NEtflix.png") }}" alt="Netflix">
    <h3>Netflix</h3>
    <p>Streaming film dan serial terbaru tanpa batas, langsung dari gadget kesayanganmu.</p>
    <span class="card-points">5000 points</span>
  </div>

  <div class="card">
    <img src="{{ asset("img/Disney.png") }}" alt="Disney">
    <h3>Disney+</h3>
    <p>Akses eksklusif ke film Disney, Pixar, Marvel, dan Star Wars favoritmu.</p>
    <span class="card-points">4000 points</span>
  </div>

  <div class="card">
    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/Google_Play_Arrow_logo.svg" alt="Google Play" class="card-logo">
    <h3>Google Play Gift Card</h3>
    <p>Dapatkan saldo Google Play untuk beli aplikasi, game, dan lainnya.</p>
    <span class="card-points">2000 points</span>
  </div>
  
</body>
</html>