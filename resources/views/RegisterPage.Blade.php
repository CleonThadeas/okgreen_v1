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
</body>
</html>
