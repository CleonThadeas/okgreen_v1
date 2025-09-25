<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OkGreen - @yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
</head>
<body>
    <nav style="background:#4CAF50; padding:10px; color:white; display:flex; justify-content:space-between; align-items:center;">
        <div>
            <a href="{{ route('dashboard') }}" style="margin-right:15px;">Dashboard</a>
            <a href="{{ route('sell-waste.index') }}" style="margin-right:15px;">Jual Sampah</a>
            <a href="{{ route('buy-waste.index') }}" style="margin-right:15px;">Beli Sampah</a>
            <a href="{{ route('edu.index') }}" style="margin-right:15px;">Edukasi</a>
        </div>
        <div>
            {{-- Tombol Notifikasi --}}
            <a href="{{ url('/notifications') }}" style="margin-right:15px; background:orange; padding:5px 10px; color:white; border-radius:5px; text-decoration:none;">
                Notifikasi
            </a>

            {{-- Tombol Profile --}}
            <a href="{{ route('profile.edit') }}" style="margin-right:15px; background:blue; padding:5px 10px; color:white; border-radius:5px; text-decoration:none;">
                Profile
            </a>
        </div>
    </nav>

    <div class="container" style="padding:20px;">
        @yield('content')
    </div>
</body>
</html>

