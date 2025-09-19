<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stok Sampah</title>
    <link rel="stylesheet" href="{{ asset('css/stok-sampah.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
  <body class="dashboard-page">
  @include('partials.navbar')
<div class="container">
  <div class="header">
    <button class="back-btn"><a href="{{ route('admin.dashboard') }}">←</a></button>
    <h2>Stok Sampah</h2>
     <input type="text" placeholder="Cari">
  </div>
    <a href="{{ route('admin.wastes.index') }}" class="btn-tambah">Lihat Stok</a>
   <section class="categories">
  <!-- Plastik -->
  <button class="cat">
    <i class="fa-solid fa-bottle-water"></i>
    <span>Plastik</span>
  </button>

  <!-- Kertas -->
  <button class="cat">
    <i class="fa-regular fa-file-lines"></i>
    <span>Kertas</span>
  </button>

  <!-- Botol Kaca -->
  <button class="cat">
    <i class="fa-solid fa-wine-bottle"></i>
    <span>Botol Kaca</span>
  </button>

  <!-- Kayu -->
  <button class="cat">
    <i class="fa-solid fa-tree"></i>
    <span>Kayu</span>
  </button>

  <!-- Besi -->
  <button class="cat">
    <i class="fa-solid fa-screwdriver-wrench"></i>
    <span>Besi</span>
  </button>

  <!-- Logam -->
  <button class="cat">
    <i class="fa-solid fa-coins"></i>
    <span>Logam</span>
  </button>

  <!-- Aluminium -->
  <button class="cat">
    <i class="fa-solid fa-industry"></i>
    <span>Aluminium</span>
  </button>

  <!-- Khusus -->
  <button class="cat">
    <i class="fa-solid fa-box"></i>
    <span>Khusus</span>
  </button>
</section>
    
<section class="chart-card">
  <h3>Sampah yang Terkumpul Bulan Ini</h3>
  <div class="chart-inner">
    <img src="{{ asset('img/bar.jpeg') }}" alt="Grafik Sampah Bulan Ini">
  </div>
</section>
</body>
</html>
