<!-- resources/views/partials/header.blade.php -->
<link rel="stylesheet" href="{{ asset('css/navbar.css') }}?v={{ time() }}">

<header class="header-navbar">
    <div class="logo">
        <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Logo">
    </div>
    <nav>
        <a href="{{ route('admin.dashboard') }}" class="{{ Route::is('berandamin') ? 'active' : '' }}">Beranda</a>
        <a href="{{ route('admin.wastes.index') }}" class="{{ Route::is('stokadmin') ? 'active' : '' }}">Stok</a>
        <a href="{{ route('admin.wastes.index') }}" class="{{ Route::is('banyaksampah') ? 'active' : '' }}">Status</a>
        <a>Edukasi</a>

    </nav>
    <div class="icons">
    <a href="{{ route('transactions.index') }}">
        <img src="{{ asset('img/bell.png') }}" alt="Notifikasi">
    </a>
    <a href="{{ route('profile.edit') }}">
        <img src="{{ asset('img/user.png') }}" alt="Profil">
    </a>
    </div>
</header>
