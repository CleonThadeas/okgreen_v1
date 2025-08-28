<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GreenLeaf</title>

    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body>
    <div class="register-container">
        <div class="header-wave"></div>

        <div class="form-box">
            <h1>Register!</h1>
            <p>Buatlah akun</p>

            <form action="{{ route('register') }}" method="POST">
                @csrf
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Sandi</label>
                <input type="password" id="password" name="password" required>

                <button type="submit" class="btn-submit">BUAT</button>
            </form>

            <p class="login-link">Do You Have Account? <a href="{{ route('login') }}">SIGN IN</a></p>
        </div>

        <div class="logo">
            <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Logo" />
            <p class="brand-name">OkGreen</p>
        </div>
    </div>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="header-shape"></div>

    <div class="login-container">
        <h1>Register!</h1>
        <p>Buat akun untuk mulai</p>

        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Sandi</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">DAFTAR</button>
        </form>

        <p class="register-text">Sudah punya akun? <a href="/login">Masuk di sini</a></p>
        <p class="forgot-password"><a href="/forgot-password">Lupa password?</a></p>

        <div class="logo">
            <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Logo GreenLeaf" />
        </div>
    </div>

</body>
</html>
