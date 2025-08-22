<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi</title>
    <link rel="stylesheet" href="{{ asset('css/notifikasi.css') }}">
</head>
<body>

    {{-- Header --}}
    @include('partials.header')

    <div class="notifikasi-container">
        <h2>Notifikasi</h2>

        {{-- Tabs --}}
        <div class="notifikasi-tabs">
            <button class="tab active" data-tab="semua">Semua <span class="badge">3</span></button>
            <button class="tab" data-tab="produk">Informasi Produk <span class="badge">1</span></button>
            <button class="tab" data-tab="balasan">Replies <span class="badge">1</span></button>
        </div>

        {{-- Daftar Notifikasi --}}
        <div class="notifikasi-list">

            {{-- Item Notifikasi 1 --}}
            <div class="notifikasi-item highlight tab-content semua produk">
                <div class="notifikasi-icon">
                    <img src="{{ asset('img/logo-greenleaf.png') }}" alt="icon">
                </div>
                <div class="notifikasi-content">
                    <p><strong>Fitur baru!</strong> 🔔</p>
                    <p>Kami dengan senang hati memperkenalkan penyempurnaan terkini dalam <b>pengalaman pembuatan templat kami.</b></p>
                    <button class="btn-primary">Coba Sekarang</button>
                    <span class="time">2 menit lalu</span>
                </div>
            </div>

            {{-- Item Notifikasi 2 --}}
            <div class="notifikasi-item tab-content semua produk">
                <div class="notifikasi-icon circle">OG</div>
                <div class="notifikasi-content">
                    <p><b>OkGreen</b> Produk anda berhasil terverifikasi untuk dijual</p>
                    <span class="time">2 menit lalu</span>
                </div>
                <div class="notifikasi-extra">
                    <img src="{{ asset('img/sample1.png') }}" alt="gambar">
                </div>
            </div>

            {{-- Item Notifikasi 3 --}}
            <div class="notifikasi-item tab-content semua balasan">
                <div class="notifikasi-icon circle">OG</div>
                <div class="notifikasi-content">
                    <p><b>Olie</b> membalas pesan pada <b>@sifanragn</b></p>
                    <blockquote>
                        Hai Sifa, terimakasih atas saran dan masukan anda, kami akan memperbaiki 😁
                    </blockquote>
                    <span class="time">2 menit lalu</span>
                </div>
            </div>
        </div>
    </div>
        <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".tab");
        const contents = document.querySelectorAll(".tab-content");

        // fungsi untuk sembunyikan semua dulu
        function hideAll() {
            contents.forEach(content => {
                content.style.display = "none";
                content.classList.remove("fade-in");
            });
        }

        // tampilkan semua awal (default "semua")
        hideAll();
        contents.forEach(content => {
            if (content.classList.contains("semua")) {
                content.style.display = "flex";
            }
        });

        // event click tab
        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                // reset tab aktif
                tabs.forEach(t => t.classList.remove("active"));
                this.classList.add("active");

                let target = this.dataset.tab;

                hideAll(); // sembunyikan dulu

                // tampilkan sesuai target
                contents.forEach(content => {
                    if (content.classList.contains(target) || target === "semua") {
                        content.style.display = "flex";
                        setTimeout(() => {
                            content.classList.add("fade-in");
                        }, 10); // delay sedikit biar transisi jalan
                    }
                });
            });
        });
    });
</script>
</body>
</html>