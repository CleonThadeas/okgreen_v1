<!DOCTYPE html>
<html lang="id">
<head>
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
          @if($errors->any())
            <div class="alert alert-danger animate-fade">
              {{ implode(', ', $errors->all()) }}
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
  </main>
</body>
</html>
