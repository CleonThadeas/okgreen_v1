<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Produk</title>
    <link rel="stylesheet" href="{{ asset('css/form.css') }}">
    
</head>
<body>

<div class="form-container">
    <div class="product-header">
        <h2>Edit Produk</h2>
    </div>

    <form action="" method="POST">
        @csrf
        <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" enable>
                    <option selected>Pilih Kategori</option>
                    <option>Plastik</option>
                    <option>Kertas</option>
                    <option>Kaleng</option>
                    <option>Kayu</option>
                    <option>Logam</option>
                    <option>Alumunium</option>
                </select>
            </div>

        <div class="form-row">
            <div class="form-group">
                <label>Jenis Sampah</label>
                <input type="text" name="jenis_sampah">
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="deskripsi">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Harga Satuan</label>
                <input type="text" name="harga_satuan">
            </div>
            <div class="form-group">
                <label>Satuan Berat</label>
                <select name="satuan_berat" enable>
                    <option selected>kg</option>
                    <option>gram</option>
                </select>
            </div>
        </div>

         <div class="stok-container">
    <label><strong>Penyesuaian Stok</strong></label>

 <div class="radio-group">
      <label><input type="radio" name="stokOption" value="set" checked> Set Nilai</label>
      <label><input type="radio" name="stokOption" value="adjust"> Tambah/Kurang</label>
    </div>
    <div class="stok-input">
      <input type="number" value="0">
      <span>Stok saat ini: 40,00 Kg</span>
    </div>
    
    <div class="form-group upload-group">
    <label>Foto Produk</label>
    <input type="file" name="foto_produk" accept="image/*">
</div>

        <button type="submit" class="btn-confirm">Konfirmasi</button>
        <button type="button" class="btn-cancel"><a href="{{ route('detailstokstaff') }}">Batalkan</a></button>
    </form>
</div>

</body>
</html>
