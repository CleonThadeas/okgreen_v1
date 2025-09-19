<!-- resources/views/partials/header.blade.php -->
<link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">

<header class="header">
    <div class="logo">
        <img src="{{ asset('img/logo2.png') }}" alt="Logo">
    </div>
    <nav>
        <a href="{{ route('dashboard') }}" class="{{ Route::is('dashboard') ? 'active' : '' }}">beranda</a>
        <a href="{{ route('sell-waste.index') }}" class="{{ Route::is('sell-waste.index') ? 'active' : '' }}">jual barang</a>
        <a href="{{ route('buy-waste.index') }}" class="{{ Route::is('buy-waste.index') ? 'active' : '' }}">beli barang</a>
        {{-- Link logout, tetap styled seperti menu lain --}}
        <a href="{{ route('logout') }}"
           class="{{ Route::is('logout') ? 'active' : '' }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
           logout
        </a>
<nav>
    {{-- hidden form untuk logout --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</nav>

    </nav>
    {{-- 
<div class="icons">
    <a href="{{ route('transactions.index') }}">
        <img src="{{ asset('img/bell.png') }}" alt="Notifikasi">
    </a>
    <a href="{{ route('profile.edit') }}">
        <img src="{{ asset('img/user.png') }}" alt="Profil">
    </a>
</div>
--}}

</header>
