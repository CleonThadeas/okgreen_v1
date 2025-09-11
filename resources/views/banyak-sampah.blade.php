<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Banyak Sampah</title>
    <link rel="stylesheet" href="{{ asset('css/banyak-sampah.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
      <div class="stats-container">
        <div class="chart-container">
          <canvas id="progressChart"></canvas>
        </div>
        <div class="legend">
          <div class="legend-item">
            <i class="done">✔</i>
            <div class="percent">75%</div>
            <div>Selesai</div>
          </div>
          <div class="legend-item">
            <i class="progress">↗</i>
            <div class="percent">25%</div>
            <div>Sedang Berlangsung</div>
          </div>
        </div>
      </div>
    </div>
  </div>
    <div class="bar-chart-card">
  <h2>Statistik Sampah</h2>
    <select id="filterSampah">
    <option value="all">Semua</option>
    <option value="Kaleng">Kaleng</option>
    <option value="Kertas">Kertas</option>
    <option value="Logam">Logam</option>
    <option value="Plastik">Plastik</option>
    <option value="Botol Kaca">Botol Kaca</option>
    <option value="Kayu">Kayu</option>
  </select>
  <canvas id="barChart"></canvas>
</div>
</body>
<script src="{{ asset('js/statistik.js') }}"></script>
<script src="{{ asset('js/bar.js') }}"></script>
</html