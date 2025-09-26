<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="{{ asset('css/tukarvoucher.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/beranda-voucher.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    @include('partials.header')

    <!-- Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="container">
        <main class="content">
            <!-- Tombol untuk buka/tutup sidebar -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
    
    <h2>Tukarkan Poin</h2>

    <div style="margin:30px 0; padding:10px; border:1px solid #ccc; background:#f9f9f9;">
        <strong>Total Poin:</strong>
        <span style="font-size:20px; color:green;">
            {{ $userPoints ?? 0 }}
        </span>
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

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
</body>
</html>
