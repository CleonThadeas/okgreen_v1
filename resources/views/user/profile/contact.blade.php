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

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami</title>
  <link rel="stylesheet" href="{{ asset('css/kontak.css') }}?v={{ time() }}">
</head>
<body>
  {{-- HEADER --}}
  @include('partials.header')

  <main>
    <div class="kontak-wrapper d-flex">
      
      {{-- SIDEBAR PROFIL --}}
      <aside class="sidebar">
        @include('user.profile.sidebar')
      </aside>

      {{-- FORM KONTAK --}}
      <div class="kontak-container animate-card flex-grow-1">
        <div class="form-section">
          <h2>Kami siap membantu dan menjawab pertanyaan Anda.</h2>

          {{-- Pesan sukses --}}
          @if(session('success'))
            <div class="alert alert-success animate-fade">
              {{ session('success') }}
            </div>
          @endif

          {{-- Pesan error --}}
          @if(session('error'))
            <div class="alert alert-danger animate-fade">
              {{ session('error') }}
            </div>
          @endif

          <form action="{{ route('contact.store') }}" method="POST" class="kontak-form animate-fade">
            @csrf
            <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
            <input type="text" name="phone" placeholder="Nomor Ponsel" value="{{ old('phone') }}" required>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            <textarea name="pesan" placeholder="Pesan" rows="3">{{ old('pesan') }}</textarea>

            <button type="submit" class="btn-kirim">Kirim</button>
          </form>
        </div>

        <div class="image-section">
          <img src="{{ asset('img/recycle.png') }}" alt="Recycle" />
        </div>
      </div>
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
  </main>
</body>
</html>
