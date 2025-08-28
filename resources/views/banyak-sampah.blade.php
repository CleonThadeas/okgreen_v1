<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Banyak Sampah</title>
    <link rel="stylesheet" href="{{ asset('css/banyak-sampah.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
  
  <body class="dashboard-page">
  @include('partials.navbar')
    
<div class="container">
    <div class="header">
       <button class="back-btn"><a href="{{ route('berandadmin') }}">←</a></button>
      <h2>Banyak Sampah</h2>
      <input type="text" placeholder="Cari">
    </div>

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