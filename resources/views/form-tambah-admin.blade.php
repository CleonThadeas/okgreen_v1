<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/form-tambah-admin.css') }}">
      <title>Form Tambah Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
  <body class="dashboard-page">
  @include('partials.navbar')
<div class="container">
  <div class="header">
    <button class="back-btn"><a href="{{ route('detailadmin') }}">←</a></button>
    <h2>Detail Admin</h2>
  </div>
        <div class="form-container">
    <div class="upload-photo">
        <label for="foto">
            <span>Unggah Foto</span>
        </label>
        <input type="file" id="foto" accept="image/*">
    </div>
        <form action="" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="Email">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" enable>
                    <option selected>Laki laki</option>
                    <option>Perempuan</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Nomor Handphone</label>
                <input type="text" name="nomor_handphone">
            </div>
            <div class="form-group">
                <label>Posisi</label>
                <input type="text" name="poaiai">
            </div>
        </div>

        <button type="submit" class="btn-confirm">Konfirmasi</button>
        <button type="button" class="btn-cancel">Batalkan</button>
    </form>
    </div>
</body>
</html>