<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Stok Admin</title>
    <link rel="stylesheet" href="{{ asset('css/stok-admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
  <body class="dashboard-page">
  @include('partials.navbar')
    
      <div class="container">
        <div class="header">
            <button class="back-btn"><a href="{{ route('stoksampah') }}">←</a></button>
            <h2>Detail stok</h2>
             <input type="text" placeholder="Cari">
        </div>
  </header>
  <table>
  <thead>
        <tr>
        <th>Foto</th>
        <th>Nama Produk</th>
        <th>Kategori</th>
        <th>Harga</th>
        <th>Berat</th>
        <th>Aksi</th>
      </tr>
    </thead>
      <tbody>
        <tr>
         <td><img src="{{ asset('img/plastikbiru.jpeg') }}" alt="Bungkus Rokok"></td>
          <td>Plastik</td>
          <td>Plastik</td>
          <td>Rp.5.000</td>
          <td>10kg</td>
        <td>
          <a href="{{ route('stokform') }}" button class="edit">✏</a></button>
          <button class="delete">🗑</button>
        </td>
        </tr>
        <tr>
          <td><img src="{{ asset('img/bungkusrokok.jpeg') }}" alt="Bungkus Rokok"></td>
          <td>Bungkus Rokok</td>
          <td>Kertas</td>
          <td>Rp.5.000</td>
          <td>20kg</td>
        <td>
          <a href="{{ route('stokform') }}" button class="edit">✏</a></button>
          <button class="delete">🗑</button>
        </td>
        </tr>
        <tr>
          <td><img src="{{ asset('img/botolplastik.jpeg') }}" alt="Bungkus Rokok"></td>
          <td>Botol Plastik</td>
          <td>Botol Plastik</td>
          <td>Rp.5.000</td>
          <td>15kg</td>
        <td>
          <a href="{{ route('stokform') }}" button class="edit">✏</a></button>
          <button class="delete">🗑</button>
        </td>
        </tr>
        <tr>
         <td><img src="{{ asset('img/kaleng.jpeg') }}" alt="Bungkus Rokok"></td>
          <td>Kaleng Coca Colla</td>
          <td>Botol Kaleng</td>
          <td>Rp.7.000</td>
          <td>23kg</td>
        <td>
          <a href="{{ route('stokform') }}" button class="edit">✏</a></button>
          <button class="delete">🗑</button>
        </td>
        </tr>
        <tr>
          <td><img src="{{ asset('img/skateboard.jpeg') }}" alt="Bungkus Rokok"></td>
          <td>Skateboard</td>
          <td>Kayu</td>
          <td>Rp.10.000</td>
          <td>5kg</td>
        <td>
          <a href="{{ route('stokform') }}" button class="edit">✏</a></button>
          <button class="delete">🗑</button>
        </td>
        </tr>
         </tr>
        <tr>
          <td><img src="{{ asset('img/tutupkaleng.jpeg') }}" alt="Bungkus Rokok"></td>
          <td>Tutup Botol Logam</td>
          <td>Logam</td>
          <td>Rp.6.000</td>
          <td>30kg</td>
        <td>
          <a href="{{ route('stokform') }}" button class="edit">✏</a></button>
          <button class="delete">🗑</button>
        </td>
        </tr>
         </tr>
        <tr>
          <td><img src="{{ asset('img/garpu.jpeg') }}" alt="Bungkus Rokok"></td>
          <td>Sendok Logam</td>
          <td>Alumunium</td>
          <td>Rp.10.000</td>
          <td>17kg</td>
        <td>
          <a href="{{ route('stokform') }}" button class="edit">✏</a></button>
          <button class="delete">🗑</button>
        </td>
        </tr>
        </tbody>
      </table>
</body>
</html>
