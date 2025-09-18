<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - GreenLeaf</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

   <!-- Greeting Section -->
<section class="greeting" id="greeting">
  <div class="container">
    <h2 id="greeting-title">
  Hello, {{ Auth::check() ? Auth::user()->name : 'Guest' }}!
</h2>
    <p id="greeting-message">Selamat datang kembali di OKGreen 🌱</p>
  </div>
</section>

  <!-- Hero Section -->
<section id="beranda" class="hero">
  <div class="hero-slider">
    <div class="carousel-slide active">
      <img src="{{ asset('img/hero1.png') }}" alt="Banner 1" />
    </div>
    <div class="carousel-slide">
      <img src="{{ asset('img/hero2.png') }}" alt="Banner 2" />
    </div>
    <div class="carousel-slide">
      <img src="{{ asset('img/hero3.png') }}" alt="Banner 3" />
    </div>
  </div>
  <div class="carousel-indicators">
    <span class="indicator active" data-slide="0"></span>
    <span class="indicator" data-slide="1"></span>
    <span class="indicator" data-slide="2"></span>
  </div>
</section>


<!-- Produk Section -->
<section class="produk">
  <div class="section-header">
    <h2>Produk Kami</h2>
    <a href="{{ route('buy-waste.index') }}" class="section-btn">➔</a>
  </div>
  <div class="produk-list">
    @forelse($wastes ?? [] as $waste)
      <div class="produk-card">
        <img src="{{ isset($waste->image) ? asset('storage/' . $waste->image) : asset('img/no-image.png') }}" alt="{{ $waste->name ?? ($waste->type_name ?? 'Produk') }}">
        <p>{{ $waste->name ?? $waste->type_name ?? 'Nama Produk' }}</p>
        <span>Rp{{ number_format(optional($waste->stock)->price ?? $waste->price_per_unit ?? 0, 0, ',', '.') }}</span>
      </div>
    @empty
      <p>Tidak ada produk tersedia</p>
    @endforelse
</div>
</section>


  <!-- Edukasi Section -->
<section class="edukasi">
  <div class="section-header">
    <h2>Edukasi</h2>
  </div>
  <div class="edukasi-content">
    <!-- Video -->
   <div class="edukasi-video">
  <iframe
    width="100%"
    height="500"
    src="https://www.youtube.com/embed/snRhl3ING0Y"
    title="Video Edukasi"
    frameborder="0"
    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
    allowfullscreen>
  </iframe>
</div>

<!-- Modal -->
<div id="imageModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="modalImage">
</div>

    <!-- Cards -->
    <div class="edukasi-cards">
      <div class="edukasi-card">
       <img src="{{ asset('img/edukasi1.png') }}" 
         data-large="{{ asset('img/isiedukasi1.png') }}" 
         alt="Edukasi 1">
        <h3>Tips Daur Ulang</h3>
        <p>Pelajari cara sederhana mendaur ulang sampah rumah tangga agar lebih ramah lingkungan.</p>
      </div>
      <div class="edukasi-card">
        <img src="{{ asset('img/edukasi2.png') }}" 
         data-large="{{ asset('img/isiedukasi2.png') }}" 
         alt="Edukasi 2">
        <h3>Hemat Energi</h3>
        <p>Matikan lampu saat tidak digunakan untuk mengurangi konsumsi listrik.</p>
      </div>
      <div class="edukasi-card">
        <img src="{{ asset('img/edukasi3.png') }}" 
         data-large="{{ asset('img/isiedukasi3.png') }}" 
         alt="Edukasi 3">
        <h3>Kurangi Plastik</h3>
        <p>Gunakan tas kain dan botol minum isi ulang untuk mengurangi limbah plastik.</p>
      </div>
    </div>
  </div>
</section>


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
  <!-- JS Carousel -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
    // Ambil nama user dari Laravel Auth
    const userName = "{{ Auth::check() ? Auth::user()->name : 'Guest' }}";

    // === GREETING SECTION ===
    const greetingTitle = document.getElementById("greeting-title");
    const greetingMessage = document.getElementById("greeting-message");

    const hour = new Date().getHours();
    let greet = "";

    if (hour >= 5 && hour < 12) {
        greet = "Selamat Pagi 🌅";
    } else if (hour >= 12 && hour < 15) {
        greet = "Selamat Siang ☀️";
    } else if (hour >= 15 && hour < 18) {
        greet = "Selamat Sore 🌇";
    } else {
        greet = "Selamat Malam 🌙";
    }

    // Update greeting text
    if (greetingTitle && greetingMessage) {
        greetingTitle.textContent = `${greet}, ${userName}!`;
        greetingMessage.textContent = "Semoga harimu menyenangkan bersama OkGreen 🌱";

        // Animasi muncul
        const greetingSection = document.getElementById("greeting");
        if (greetingSection) {
            greetingSection.style.opacity = 0;
            greetingSection.style.transform = "translateY(-20px)";

            setTimeout(() => {
                greetingSection.style.transition = "all 0.8s ease";
                greetingSection.style.opacity = 1;
                greetingSection.style.transform = "translateY(0)";
            }, 200);
        }
    }

    // === HERO CAROUSEL AUTO-SLIDE ===
    let currentSlide = 0;
    const slides = document.querySelectorAll(".carousel-slide");
    const indicators = document.querySelectorAll(".indicator");

    function showSlide(index) {
      slides.forEach((slide, i) => {
        slide.classList.remove("active");
        indicators[i].classList.remove("active");
        if (i === index) {
          slide.classList.add("active");
          indicators[i].classList.add("active");
        }
      });
    }

    function nextSlide() {
      currentSlide = (currentSlide + 1) % slides.length;
      showSlide(currentSlide);
    }

    let slideInterval = setInterval(nextSlide, 4000);

    indicators.forEach((indicator) => {
      indicator.addEventListener("click", () => {
        clearInterval(slideInterval);
        const index = parseInt(indicator.getAttribute("data-slide"));
        currentSlide = index;
        showSlide(index);
        slideInterval = setInterval(nextSlide, 4000);
      });
    });
  });
</script>
<script>
  // Scroll Reveal Animation (Produk & Edukasi)
  const reveals = document.querySelectorAll(".reveal, .produk-card");

  function revealOnScroll() {
    const windowHeight = window.innerHeight;
    reveals.forEach((el, index) => {
      const elementTop = el.getBoundingClientRect().top;
      if (elementTop < windowHeight - 100) {
        setTimeout(() => {
          el.classList.add("active");
        }, index * 150); // kasih delay biar berurutan
      }
    });
  }

  window.addEventListener("scroll", revealOnScroll);
  window.addEventListener("load", revealOnScroll);

  // Animasi tombol play di Edukasi Section
  const playBtn = document.querySelector(".play-btn");
  if (playBtn) {
    playBtn.addEventListener("mouseenter", () => {
      playBtn.classList.add("clicked");
      setTimeout(() => playBtn.classList.remove("clicked"), 300);
    });
  }

  // Ambil elemen modal
const modal = document.getElementById("imageModal");
const modalImg = document.getElementById("modalImage");
const closeBtn = document.querySelector(".close");

// Ambil semua gambar card
document.querySelectorAll(".edukasi-card img").forEach(img => {
  img.addEventListener("click", function() {
    const largeSrc = this.getAttribute("data-large"); // ambil foto besar
    modal.style.display = "block";
    modalImg.src = largeSrc;
  });
});

// Tombol close
closeBtn.onclick = function() {
  modal.style.display = "none";
};

</script>


</body>
</html>
