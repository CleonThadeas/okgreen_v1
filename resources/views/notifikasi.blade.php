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

    <div class="container notifikasi-container">
        <h2>Notifikasi Saya</h2>

        {{-- Tombol tandai semua dibaca --}}
        <form method="POST" action="{{ route('notifications.readAll') }}" style="margin-bottom:15px;">
            @csrf
            <button type="submit">Tandai Semua Dibaca</button>
        </form>

        {{-- Tabs --}}
        <div class="notifikasi-tabs" style="margin-bottom:10px;">
            <button class="tab active" data-tab="semua">Semua <span class="badge">{{ $notifications->count() }}</span></button>
            <button class="tab" data-tab="produk">Informasi Produk <span class="badge">{{ $notifications->where('type','product')->count() }}</span></button>
            <button class="tab" data-tab="balasan">Replies <span class="badge">{{ $notifications->where('type','reply')->count() }}</span></button>
        </div>

        {{-- Daftar Notifikasi --}}
        <div class="notifikasi-list">
            @forelse($notifications as $notif)
                <div class="notifikasi-item tab-content semua {{ $notif->type }}">
                    <div class="notifikasi-icon" style="margin-right:10px;">
                        @if($notif->icon)
                            <img src="{{ asset($notif->icon) }}" alt="icon">
                        @else
                            <div class="circle">OG</div>
                        @endif
                    </div>
                    <div class="notifikasi-content">
                        <a href="{{ route('notifications.show', $notif->id) }}" style="text-decoration:none; color:#333;">
                            <p><strong>{{ $notif->title }}</strong></p>
                            <small>{{ $notif->created_at->format('d M Y H:i') }}</small>
                            <p>{{ $notif->message }}</p>
                        </a>
                    </div>
                </div>
            @empty
                <p>Tidak ada notifikasi.</p>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div style="margin-top:10px;">
            {{ $notifications->links() }}
        </div>
    </div>

    {{-- Script tab --}}
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".tab");
        const contents = document.querySelectorAll(".tab-content");

        function hideAll() {
            contents.forEach(content => {
                content.style.display = "none";
                content.classList.remove("fade-in");
            });
        }

        // tampilkan semua awal
        hideAll();
        contents.forEach(content => {
            if (content.classList.contains("semua")) {
                content.style.display = "flex";
            }
        });

        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                tabs.forEach(t => t.classList.remove("active"));
                this.classList.add("active");

                let target = this.dataset.tab;
                hideAll();
                contents.forEach(content => {
                    if (content.classList.contains(target) || target === "semua") {
                        content.style.display = "flex";
                        setTimeout(() => content.classList.add("fade-in"), 10);
                    }
                });
            });
        });
    });
    </script>

</body>
</html>
