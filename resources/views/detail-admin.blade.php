<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Admin</title>
    <link rel="stylesheet" href="{{ asset('css/detail-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
  <header class="navbar">
    <div class="logo-wrapper">
      <span class="logo">OKGreen</span>
    </div>
    <nav>
      <ul>
        <li><a href="{{ route('berandadmin') }}">Beranda</a></li>
        <li><a href="{{ route('stokadmin') }}">Stok</a></li>
        <li><a href="{{ route('banyaksampah') }}">Status</a></li>
        <li>Edukasi</a></li>
      </ul>
    </nav>
    <div class="right-icons">
      <i class="fas fa-bell"></i>
      <i class="fas fa-user-circle"></i>
    </div>
  </header>

<div class="container">
  <div class="header">
    <button class="back-btn">←</button>
    <h2>Detail Admin</h2>
     <input type="text" placeholder="Cari">
  </div>

<div class="dashboard-cards">
      <div class="card">
        <div class="card-header">
          <h3>Pengguna</h3>
          <span class="icon purple">📅</span>
        </div>
        <h2><a href="{{ route('detailpengguna') }}">20</a></h2>
        <p><span class="text-bold">14</span> Aktif</p>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Banyak Sampah</h3>
          <span class="icon blue">📋</span>
        </div>
        <h2><a href="{{ route('banyaksampah') }}">132</a></h2>
        <p><span class="text-bold">32</span> Belum Selesai</p>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Admin</h3>
          <span class="icon red">👥</span>
        </div>
        <h2><a href="{{ route('detailadmin') }}">10</a></h2>
        <p><span class="text-bold">2</span> Aktif</p>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Status</h3>
          <span class="icon green">♻️</span>
        </div>
         <h2><a href="{{ route('banyaksampah') }}">75%</a></h2>
        <p><span class="text-green">25%</span> Belum Selesai</p>
      </div>
    </div>

    <div class="header-admin">
    <h2>Admin</h2>
    <a href="{{ route('tambahadmin') }}" class="btn-tambah">Tambah Admin</a>
    </div>

    <div class="admin-list">
     <div class="admin-grid">
    <div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
      <button class="edit">✏</button>
          <button class="delete">🗑</button>
    </div>
    <div class="admin-card">
     <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
      <button class="edit">✏</button>
          <button class="delete">🗑</button>
      
  </div>
    <div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
       <button class="edit">✏</button>
          <button class="delete">🗑</button>
</div>
<div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
       <button class="edit">✏</button>
          <button class="delete">🗑</button>
</div>
<div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
       <button class="edit">✏</button>
          <button class="delete">🗑</button>
</div>
<div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
       <button class="edit">✏</button>
          <button class="delete">🗑</button>
</div>
<div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
       <button class="edit">✏</button>
          <button class="delete">🗑</button>
</div>
<div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
       <button class="edit">✏</button>
          <button class="delete">🗑</button>
</div>
<div class="admin-card">
      <img src="{{ asset('img/jian.jpeg') }}" alt="Jian">
      <div class="admin-info">
        <strong>Jiman</strong> <span class="status active">Aktif</span><br>
        <small>Super Admin</small><br>
        <small>Morthelp@example.com</small>
      </div>
       <button class="edit">✏</button>
          <button class="delete">🗑</button>
</div>
</body>
</html>