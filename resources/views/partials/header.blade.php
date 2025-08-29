<!-- resources/views/partials/header.blade.php -->
<link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">

<header class="header">
    <div class="logo">
        <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Logo">
    </div>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}">beranda</a>
        <a href="{{ route('sell-waste.index') }}" class="{{ Route::is('sell-waste.index') ? 'active' : '' }}">jual barang</a>
        <a href="{{ route('buy-waste.index') }}" class="{{ Route::is('buy-waste.index') ? 'active' : '' }}">beli barang</a>
    </nav>
    {{-- 
<div class="icons">
    <a href="{{ route('notifikasi') }}">
        <img src="{{ asset('img/bell.png') }}" alt="Notifikasi">
    </a>
    <a href="{{ route('profil') }}">
        <img src="{{ asset('img/user.png') }}" alt="Profil">
    </a>
</div>
--}}

</header>
