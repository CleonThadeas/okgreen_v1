<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>OKGreen - Daur Ulang Plastik</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <!-- Header -->
  <header class="navbar">
    <div class="logo">OKGreen</div>
    <nav>
      <ul>
        <li><a href="#beranda">Beranda</a></li>
        <li><a href="#tentang">Tentang Kami</a></li>
        <li><a href="#fitur">Fitur</a></li>
        <li><a href="#kontak">Kontak Kami</a></li>
        <li><a href="{{ route('login') }}" class="btn-masuk">Gabung</a></li>
      </ul>
    </nav>
  </header>

  <!-- Hero Section -->
  <section id="beranda" class="hero">
  <div class="hero-slider">
    <div class="carousel-slide active">
      <img src="{{ asset('img/banner1.png') }}" alt="Banner 1" />
    </div>
    <div class="carousel-slide">
      <img src="{{ asset('img/banner2.png') }}" alt="Banner 2" />
    </div>
    <div class="carousel-slide">
      <img src="{{ asset('img/banner3.png') }}" alt="Banner 3" />
    </div>
  </div>
  <div class="carousel-indicators">
    <span class="indicator active" data-slide="0"></span>
    <span class="indicator" data-slide="1"></span>
    <span class="indicator" data-slide="2"></span>
  </div>
</section>

  <!-- Tentang Kami -->
<section id="tentang" class="tentang">
  <div class="tentang-container">
    <div class="tentang-text">
      <div class="section-title">
        <img src="{{ asset('img/info-icon.png') }}" alt="icon" />
        <h2>Tentang Kami</h2>
      </div>
      <p>
        OkGreen adalah platform digital berbasis web dan aplikasi mobile yang memudahkan masyarakat Indonesia untuk menjual dan mengelola sampah secara bertanggung jawab. Kami menerima berbagai jenis sampah seperti plastik, kertas, kaca, logam, hingga minyak jelantah, yang dapat dijual ke platform atau sesama pengguna.
        <br /><br />
        Dengan sistem penjemputan terjadwal dan fitur marketplace internal, OkGreen hadir untuk membentuk budaya baru: sampah bukan untuk dibuang, tapi dimanfaatkan. Kami percaya bahwa pengelolaan sampah yang cerdas dapat membuka peluang ekonomi dan menjaga lingkungan secara berkelanjutan.
      </p>
    </div>
    <div class="tentang-logo">
      <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Logo GreenLeaf" />
    </div>
  </div>
</section>


  <!-- FAQ -->
<section id="faq" class="faq">
  <div class="section-title">
    <img src="{{ asset('img/question-icon.png') }}" alt="icon">
    <h2>Pertanyaan Umum</h2>
  </div>

  <div class="faq-scroll-container"><!-- Tambahkan ini -->
    <div class="faq-item">
      <h3>Berapa lama waktu yang dibutuhkan untuk penjemputan setelah pengajuan?</h3>
      <p>Penjemputan akan dilakukan maksimal 1x24 jam setelah pengajuan disetujui.</p>
    </div>
    <div class="faq-item">
      <h3>Jenis sampah apa saja yang bisa saya jual melalui OKGreen?</h3>
      <p>Kami menerima berbagai jenis sampah daur ulang seperti plastik, kertas, logam, dan kaca.</p>
    </div>
    <div class="faq-item">
      <h3>Bagaimana cara saya menjual sampah saya?</h3>
      <p>Kamu bisa menggunakan fitur pickup atau drop-off di titik terdekat.</p>
    </div>
    <div class="faq-item">
      <h3>Bagaimana saya mendapatkan poin dari menjual sampah?</h3>
      <p>Setiap penjualan yang berhasil akan mendapatkan poin yang bisa ditukar reward.</p>
    </div>
    <div class="faq-item">
      <h3>Apakah saya harus memisahkan sampahnya?</h3>
      <p>Ya, mohon pisahkan sampah sesuai jenisnya agar proses daur ulang lebih cepat dan efisien.</p>
    </div>
    <div class="faq-item">
      <h3>Apakah bisa menjual sampah dari luar kota?</h3>
      <p>Untuk saat ini, layanan kami hanya tersedia di area tertentu. Silakan cek daftar wilayah yang tersedia di halaman utama.</p>
    </div>
    <div class="faq-item">
      <h3>Apakah saya bisa menjual sampah yang sudah dicacah/dipotong kecil?</h3>
      <p>Bisa, selama masih bisa dikategorikan dan tidak tercampur dengan sampah lain.</p>
    </div>
    <!-- Tambahkan pertanyaan lain di sini -->
  </div><!-- Tutup scroll container -->
</section>

<script>
  // FAQ toggle
  const faqItems = document.querySelectorAll('.faq-item');

  faqItems.forEach(item => {
    item.querySelector('h3').addEventListener('click', () => {
      item.classList.toggle('open');
    });
  });

  // Hero carousel auto-slide
  let currentSlide = 0;
  const slides = document.querySelectorAll('.carousel-slide');
  const indicators = document.querySelectorAll('.indicator');

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.remove('active');
      indicators[i].classList.remove('active');
      if (i === index) {
        slide.classList.add('active');
        indicators[i].classList.add('active');
      }
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  let slideInterval = setInterval(nextSlide, 4000); // Ganti slide setiap 4 detik

  indicators.forEach(indicator => {
    indicator.addEventListener('click', () => {
      clearInterval(slideInterval); // stop autoplay
      const index = parseInt(indicator.getAttribute('data-slide'));
      currentSlide = index;
      showSlide(index);
      slideInterval = setInterval(nextSlide, 4000); // restart autoplay
    });
  });
</script>

<body class="custom-body">

<!-- Header -->
<div class="section-header">
    <!-- Bungkus header dalam .section-wrapper -->
<div class="section-wrapper">
    <p class="section-subtitle">Layanan yang Kami Sediakan</p>
        <p class="section-subdesc">Lihat semua layanan yang kami sediakan</p>
  </div>
</div>

</div>

<!-- Layanan -->
<div class="service-grid">
    @php
        $services = [
            ['icon' => '🗑️', 'title' => 'Sampah Tersedia', 'desc' => 'Jual sampah ataupun beli sampah semuanya tersedia.'],
            ['icon' => '📜', 'title' => 'Riwayat Pembelian', 'desc' => 'Lacak semua aktivitas sebelumnya, lengkap dengan detailnya.'],
            ['icon' => '📍', 'title' => 'Lokasi Pickup', 'desc' => 'Cek titik lokasi penjual untuk melakukan pickup.'],
            ['icon' => '🔔', 'title' => 'Notifikasi', 'desc' => 'Dapatkan info secara real-time setiap harinya.'],
            ['icon' => '📖', 'title' => 'Edukasi', 'desc' => 'Belajar tentang GreenTech untuk membuat alam sehat kembali.'],
            ['icon' => '🎁', 'title' => 'Poin', 'desc' => 'Dapatkan poin dari jual sampah atau reward untuk ditukar uang.'],
        ];
    @endphp

    @foreach ($services as $service)
        <div class="service-card">
            <div class="service-icon">{{ $service['icon'] }}</div>
            <h3 class="service-title">{{ $service['title'] }}</h3>
            <p class="service-desc">{{ $service['desc'] }}</p>
        </div>
    @endforeach
</div>

<!-- Jenis Sampah -->
<div class="section-header">
    <h2 class="section-subtitle">Jenis Sampah</h2>
    <p class="section-subdesc">Lihat semua jenis sampah yang kami daur ulang</p>
</div>

<div class="types-grid">
    @php
        $types = ['Plastik', 'Kertas', 'Botol Kaca', 'Kayu', 'Besi', 'Logam', 'Aluminium', 'Khusus'];
        $icons = ['🛍️', '📄', '🍾', '🪵', '🛠️', '💰', '🥫', '❗'];
    @endphp

    @foreach ($types as $index => $type)
        <div class="type-card">
            <div class="type-icon">{{ $icons[$index] }}</div>
            <span class="type-name">{{ $type }}</span>
        </div>
    @endforeach
</div>

<!-- Footer -->
<footer class="footer">
    <div class="footer-grid">
        <div><h3 class="footer-brand">GreenLeaf</h3></div>
        <div>
            <h4 class="footer-heading">Perusahaan</h4>
            <ul>
                <li><a href="#tentangkami">Tentang Kami</a></li>
                <li><a href="#kontakkami">Kontak Kami</a></li>
            </ul>
        </div>
        <div>
            <h4 class="footer-heading">Layanan Pelanggan</h4>
            <ul>
                <li><a href="#akunku">Akunku</a></li>
                <li><a href="#faq">FAQ</a></li>
            </ul>
        </div>
        <div>
            <h4 class="footer-heading">Info Kontak</h4>
            <ul>
                <li>+0123-456-789</li>
                <li><a href="mailto:example@gmail.com" class="email-link">example@gmail.com</a></li>
                <li>Jalbud, Jabar. 1234</li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        © 2024 Furniture. All Rights Reserved. | Indonesia
    </div>
</footer>
</body>
