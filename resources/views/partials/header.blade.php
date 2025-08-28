<!-- resources/views/partials/header.blade.php -->
<link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">

<header class="header">
    <div class="logo">
        <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Logo">
    </div>
    <nav>
        <a href="{{ route('beranda') }}" class="{{ Route::is('beranda') ? 'active' : '' }}">beranda</a>
        <a href="{{ route('jual-barang') }}" class="{{ Route::is('jual-barang') ? 'active' : '' }}">jual barang</a>
        <a href="{{ route('belibarang') }}" class="{{ Route::is('belibarang') ? 'active' : '' }}">beli barang</a>
    </nav>
    <div class="icons">
    <a href="{{ route('notifikasi') }}">
        <img src="{{ asset('img/bell.png') }}" alt="Notifikasi">
    </a>
    <a href="{{ route('profil') }}">
        <img src="{{ asset('img/user.png') }}" alt="Profil">
    </a>
    </div>
</header>
