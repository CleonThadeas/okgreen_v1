<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Orderan</title>
  <link rel="stylesheet" href="{{ asset('css/list-orderan.css') }}">
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
        <li><a href="{{ route('admin.dashboard') }}">Beranda</a></li>
        <li><a href="{{ route('admin.wastes.index') }}">Stok</a></li>
        <li><a href="{{ route('admin.wastes.index') }}">Status</a></li>
      </ul>
      </div>
    </nav>
    <div class="right-icons">
      <i class="fas fa-bell"></i>
      <i class="fas fa-user-circle"></i>
    </div>
  </header>

  <div class="container">
    <div class="header">
      <h2>List Orderan</h2>
      <input type="text" placeholder="Cari">
    </div>
  </div>

  <div class="filter-bar">
  <div class="filter-item icon">
    <span>Filter Berdasarkan</span>
  </div>
  
  <div class="filter-item">
   <label for="bulan">Pilih Bulan:</label>
<select id="bulan" name="bulan">
  <option value="1">Januari</option>
  <option value="2">Februari</option>
  <option value="3">Maret</option>
  <option value="4">April</option>
  <option value="5">Mei</option>
  <option value="6">Juni</option>
  <option value="7">Juli</option>
  <option value="8">Agustus</option>
  <option value="9">September</option>
  <option value="10">Oktober</option>
  <option value="11">November</option>
  <option value="12">Desember</option>
</select>
  </div>

  <div class="filter-item">
    <select>
      <option>Plastik</option>
      <option>Kertas</option>
      <option>Alumunium</option>
      <option>Logam</option>
      <option>Besi</option>
      <option>Kayu</option>
    </select>
  </div>

  <div class="filter-item">
    <select>
      <option>Order Status</option>
      <option>Pending</option>
      <option>Selesai</option>
      <option>Batal</option>
    </select>
  </div>

  <div class="filter-item reset">
    <span>🔄</span>
    <a href="#">Reset Filter</a>
  </div>
</div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama Pengguna</th>
          <th>Alamat</th>
          <th>Tanggal</th>
          <th>Kategori</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>0001</td>
          <td>Jian</td>
          <td>089 Kutch Green Apt. 448</td>
          <td>04 Sep 2019</td>
          <td>Plastik</td>
            <td><span class="status-btn status-berhasil">Berhasil</span></td>
          </td>
        </tr>
        <tr>
          <td>0002</td>
          <td>Jian</td>
          <td>979 Immanuel Ferry Suite 526</td>
          <td>04 Mei 2019</td>
          <td>Kertas</td>
           <td><span class="status-btn status-proses">Proses</span></td>
          </td>
        </tr>
            <td>0003</td>
          <td>Jian</td>
          <td>8587 Frida Ports</td>
          <td>04 Mei 2019</td>
          <td>Kertas</td>
           <td><span class="status-btn status-proses">Proses</span></td>
        <tr>
            <td>0004</td>
          <td>Jian</td>
          <td>768 Destiny Lake Suite 600</td>
          <td>04 Mei 2019</td>
          <td>Kertas</td>
          <td><span class="status-btn status-berhasil">Berhasil</span></td>
          </td>
        <tr>
            <td>0005</td>
          <td>Jian</td>
          <td>042 Mylene Throughway</td>
          <td>04 Mei 2019</td>
          <td>Kertas</td>
          <td><span class="status-btn status-berhasil">Berhasil</span></td>
          </td>
        </tr>
    </div>
</div>
</div>
</body>
</html>
