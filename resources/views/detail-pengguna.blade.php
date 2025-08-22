<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/detail-pengguna.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title>Detail Pengguna</title>
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
  <div>
    <div class="container">
  <div class="header">
    <button class="back-btn">←</button>
    <h2>Detail Pengguna</h2>
  </div>

  <div class="container">
    <div class="header">
      <h2>Beranda</h2>
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
    <table>
  <thead>
        <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>No Telf</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
      <tbody>
        <tr>
          <td>Jiman</td>
          <td>Morthelp@example.com</td>
          <td>0987654321</td>
          <td>Offline</td>
        <td>
          <button class="delete">🗑</button>
        </td>
        </tr>
        <tr>
          <td>Jiman</td>
          <td>Morthelp@example.com</td>
          <td>0987654321</td>
          <td>Offline</td>
        <td>
          <button class="delete">🗑</button>
        </td>
        </tr>
        </tbody>
      </table>
  </div>
</body>
</html>