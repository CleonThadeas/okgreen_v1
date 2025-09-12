<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GreenLeaf</title>

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="header-shape"></div>

    <div class="auth-container">
        <h1>Register!</h1>
        <p>Buat akun untuk mulai</p>

        <!-- Pesan error -->
        @if ($errors->any())
            <div class="error-msg">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form register -->
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name">Username</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="username">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="email">

            <label for="password">Sandi</label>
            <input type="password" id="password" name="password" required autocomplete="new-password">

            <label for="password_confirmation">Konfirmasi Sandi</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">

            <button type="submit" class="btn-submit">DAFTAR</button>
        </form>

        <p class="register-text">Sudah punya akun?
            <a href="{{ route('login') }}">Masuk di sini</a>
        </p>

        <div class="logo">
            <img src="{{ asset('img/logo1.png') }}" alt="Logo GreenLeaf" />
        </div>
    </div>

</body>
</html>
