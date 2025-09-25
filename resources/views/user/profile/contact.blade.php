<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}?v={{ time() }}">
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

	<h3>Hubungi Kami</h3>
        <form>
            <div class="mb-3">
                <label>Nama</label>
                <input type="text" class="form-control">
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" class="form-control">
            </div>
            <div class="mb-3">
                <label>Pesan</label>
                <textarea class="form-control" rows="4"></textarea>
            </div>
            <button type="submit" class="btn btn-success">Kirim</button>
        </form>
    </div>

    <script>
        // Preview foto profil
        document.getElementById("fileInput").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById("profileImage").src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });

        // Toggle sidebar
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
</body>
</html>
