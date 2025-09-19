<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Admin</title>
    <link rel="stylesheet" href="{{ asset('css/profileadmin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <body class="dashboard-page">
    @include('partials.navbar')

    <div class="container">
        <div class="header">
            <button class="back-btn"><a href="{{ route('berandadmin') }}">←</a></button>
            <h2>Informasi Pribadi</h2>
    </div>
    
   <div class="container">
      <div class="profile">
        <div class="avatar">
          <img src="https://cdn-icons-png.flaticon.com/512/847/847969.png" alt="Foto Profil">
        </div>
        <h3>Pengguna</h3>
        <p>Tetapkan Foto</p>
        <input type="file" id="foto" accept="image/*">
      </div>
    </aside>
         <main class="main-content">
      <form class="form">
        <div class="form-group">
          <label for="nama">Nama</label>
          <input type="text" id="nama" placeholder="Masukkan Nama">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" placeholder="Masukkan Email">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" placeholder="Masukkan Password">
        </div>
        <div class="btn-group">
          <button type="reset" class="btn discard">Buang Perubahan</button>
          <button type="submit" class="btn save">Simpan Perubahan</button>
        </div>
      </form>
    </main>
  </div>
</body>
</html>