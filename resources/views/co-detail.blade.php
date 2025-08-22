<?php
// Ambil data dari query string (JSON produk dari belibarang)
// Default produk (fallback)
$produk = [
    'nama' => 'Nama Produk Contoh',
    'gambar' => 'img/sample1.png',
    'deskripsi' => 'Deskripsi produk belum tersedia.'
];

// Cek apakah ada data di URL
if (isset($_GET['data'])) {
    $decoded = json_decode($_GET['data'], true);

    // Jika format data berupa array (misal dari list produk)
    if (is_array($decoded)) {
        if (isset($decoded[0]) && is_array($decoded[0])) {
            $produk = array_merge($produk, $decoded[0]);
        } else {
            $produk = array_merge($produk, $decoded);
        }
    }
}

// Contoh variasi gambar (dummy, bisa diganti dari DB nanti)
$variasiGambar = [
    'img/sample1.png',
    'img/sample2.png',
    'img/sample3.png'
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($produk['nama']) ?></title>
    <link rel="stylesheet" href="{{ asset('css/detailbarang.css') }}?v={{ time() }}">
</head>
<body>

@include('partials.header')


<main class="detail-produk-container">

    <!-- Gambar Produk -->
    <div class="produk-gambar">
        <a href="javascript:history.back()" class="btn-kembali">← back to list</a>
        <img id="main-image" src="<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama']) ?>">
    </div>

    <!-- Informasi Produk -->
    <div class="produk-info">

        <!-- Tag & Nama -->
        <span class="tag-hotsale">HOTSALE</span>
        <h1><?= htmlspecialchars($produk['nama']) ?></h1>

        <!-- Kategori + Rating -->
        <div class="kategori-rating">
            <span class="kategori">Cans</span>
            <span class="rating">
                ⭐⭐⭐⭐⭐ <span class="rating-score">4.9</span> (2130 reviews)
            </span>
        </div>

        <!-- Deskripsi -->
        <p class="deskripsi-label">Deskripsi:</p>
        <div class="deskripsi">
            <?php foreach ($variasiGambar as $img): ?>
                <img src="<?= htmlspecialchars($img) ?>" alt="thumb" class="thumb-produk" onclick="gantiGambar('<?= htmlspecialchars($img) ?>')">
            <?php endforeach; ?>
            <span><?= htmlspecialchars($produk['deskripsi']) ?></span>
        </div>

        <!-- Pilihan Size -->
        <div class="size-container">
            <span>Size:</span>
            <button class="size">1.5 kg</button>
            <button class="size">1 kg</button>
            <button class="size">500 gr</button>
            <button class="size">250 gr</button>
        </div>
    </div>
</main>

<!-- Bagian bawah sticky -->
<div class="bottom-bar">
    <div class="bottom-left">
        <img src="<?= htmlspecialchars($produk['gambar']) ?>" alt="thumb" class="thumb-bottom">
        <span class="bottom-nama"><?= htmlspecialchars($produk['nama']) ?></span>
    </div>
    <div class="bottom-right">
        <div class="qty-control">
            <button class="qty-btn minus">-</button>
            <input type="number" value="1" min="1" id="qtyInput">
            <button class="qty-btn plus">+</button>
        </div>
        <a href="{{ route('checkout') }}" class="btn-beli">Beli</a>
    </div>
</div>

<script>
function gantiGambar(src) {
    document.getElementById('main-image').src = src;
}
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
      const minusBtn = document.querySelector(".qty-btn.minus");
      const plusBtn = document.querySelector(".qty-btn.plus");
      const qtyInput = document.getElementById("qtyInput");
    
      minusBtn.addEventListener("click", () => {
        let value = parseInt(qtyInput.value);
        if (value > parseInt(qtyInput.min)) {
          qtyInput.value = value - 1;
        }
      });
    
      plusBtn.addEventListener("click", () => {
        let value = parseInt(qtyInput.value);
        qtyInput.value = value + 1;
      });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
      // Select semua tombol size
      const sizeButtons = document.querySelectorAll(".size");
    
      sizeButtons.forEach(button => {
        button.addEventListener("click", () => {
          // hapus active dari semua button
          sizeButtons.forEach(btn => btn.classList.remove("active"));
    
          // tambahkan active hanya ke button yang diklik
          button.classList.add("active");
        });
      });
    });
</script>



</body>
</html>
