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
          <div class="kontak-container">
            <div class="form-section">
              <div class="form-header">
                <button class="menu-toggle" onclick="toggleSidebar()">
                  <i class="fas fa-bars"></i>
                </button>
                  <h2>Kami siap membantu dan menjawab pertanyaan Anda.</h2>
    </div>

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

    <form id="kontakForm" action="{{ route('contact.store') }}" method="POST" class="kontak-form">
      @csrf
      <p>
        <label>Subject</label><br>
        <input type="text" name="subject" value="{{ old('subject') }}" required>
      </p>
      <p>
        <label>Message</label><br>
        <textarea name="message" rows="6" required>{{ old('message') }}</textarea>
      </p>
     <button type="submit">Kirim</button>
    </form>
</div>
        <div class="image-section">
          <img src="{{ asset('img/recycle.jpeg') }}" alt="Recycle" />
        </div>
      </div>
    </div>

    
<!-- Overlay Modal -->
<div class="overlay" id="overlay">
  <div class="modal">
    <img src="{{ asset('img/logo.png') }}" alt="Logo">
    <h3>Pesan Terkirim!</h3>
    <p>Pesan Anda telah berhasil terkirim. Kami akan segera menghubungi Anda kembali.</p>
    <button class="btn-abaikan" onclick="closeModal()">Abaikan</button>
    <button class="btn-masuk" onclick="closeModal()">Masuk</button>
  </div>
</div>

  </main>
</body>
</html>
