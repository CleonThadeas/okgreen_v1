<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>OKGreen - Daur Ulang Plastik</title>
  <link rel="stylesheet" href="css/style.css"/>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
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
        <li><a href="#" class="btn-masuk">Gabung</a></li>
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
</body>
</html>
