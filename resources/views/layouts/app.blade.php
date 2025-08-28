<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OkGreen - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav style="background:#4CAF50; padding:10px; color:white;">
        <a href="{{ route('dashboard') }}" style="margin-right:15px;">Dashboard</a>
        <a href="{{ route('sell-waste.index') }}" style="margin-right:15px;">Jual Sampah</a>
        <a href="{{ route('buy-waste.index') }}" style="margin-right:15px;">Beli Sampah</a>
        <a href="{{ route('edu.index') }}" style="margin-right:15px;">Edukasi</a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" style="background:red; color:white; border:none; padding:5px 10px; cursor:pointer;">Logout</button>
        </form>
    </nav>

    <div class="container" style="padding:20px;">
        @yield('content')
    </div>
</body>
</html>
