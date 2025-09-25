<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami</title>
  <link rel="stylesheet" href="{{ asset('css/kontak.css') }}?v={{ time() }}">
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
        
      {{-- FORM KONTAK --}}
      <div class="kontak-container animate-card flex-grow-1">
        <div class="form-section">
             <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
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
          <img src="{{ asset('img/recycle.jpeg') }}" alt="Recycle" />
        </div>
      </div>
    </div>

    <script>
        // Toggle sidebar
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
  </main>
</body>
</html>
