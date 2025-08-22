<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Banyak Sampah</title>
      <link rel="stylesheet" href="{{ asset('css/banyak-sampah.css') }}">
  <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <header class="navbar">
    <div class="logo-wrapper">
      <span class="logo">OKGreen</span>
    </div>
    <nav>
      <ul>
       <li><a href="{{ route('berandadmin') }}">Beranda</a></li>
        <li><a href="{{ route('stokadmin') }}">Stok</a></li>
        <li><a href="{{ route('banyaksampah') }}">Status</a></li>
        <li>Edukasi</a></li>
      </ul>
    </nav>
    <div class="right-icons">
      <i class="fas fa-bell"></i>
      <i class="fas fa-user-circle"></i>
    </div>
  </header>

<div class="container">
  <div class="header">
    <button class="back-btn">←</button>
    <h2>Banyak Sampah</h2>
     <input type="text" placeholder="Cari">
  </div>
    </header>

    <div class="statistik-card">
    <h2>Statistik</h2>
    <div class="statistik-content">
        <img src="{{ asset('img/statistik.png') }}" alt="Statistik" class="statistik-img">
        
        <div class="statistik-info">
            <div class="info-item">
                <span class="icon green">✔</span>
                <strong>75%</strong>
                <p>Selesai</p>
            </div>
            <div class="info-item">
                <span class="icon orange">↗</span>
                <strong>25%</strong>
                <p>Sedang Berlangsung</p>
            </div>
        </div>
    </div>

    <section class="chart-card">
  <h3>Sampah yang Terkumpul Bulan Ini</h3>
  <div class="chart-inner">
    <img src="{{ asset('img/bar.jpeg') }}" alt="Grafik Sampah Bulan Ini">
  </div>
</section>
</body>
</html>