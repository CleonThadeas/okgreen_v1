<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GreenLeaf</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    <div class="header-shape"></div>

    <div class="login-container">
        <h1>Selamat Datang!</h1>
        <p>Masuk atau Buat akun</p>

        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        <form action="#" method="POST">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Sandi</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">MASUK</button>
        </form>

        <p class="register-text">Tidak punya akun? <a href="/register" class="#register">Register</a></p>
       <p class="forgot-password"><a href="/forgot-password">Lupa password?</a></p>

        <div class="logo">
            <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Logo GreenLeaf" />
        </div>
    </div>

</body>
</html>
