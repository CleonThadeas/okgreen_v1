<!-- resources/views/partials/header.blade.php -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}?v={{ time() }}">

<header class="header-navbar">
    <div class="logo">
        <img src="{{ asset('img/logo2.png') }}" alt="Logo">
    </div>
    <nav>
        <a href="{{ route('detailstokstaff') }}" class="{{ Route::is('detailstokstaff') ? 'active' : '' }}">Beranda</a>
    </nav>
    <div class="icons">
    <a href="{{ route('notifikasi') }}">
        <img src="{{ asset('img/bell.png') }}" alt="Notifikasi">
    </a>
    <a href="{{ route('profileadmin') }}">
        <img src="{{ asset('img/user.png') }}" alt="Profil">
    </a>
    </div>
</header>
