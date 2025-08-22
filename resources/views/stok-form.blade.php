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
        <img src="{{ asset('img/bungkusrokok.jpeg') }}" alt="Bungkus Rokok">
        <h2>Bungkus Rokok <span class="edit-icon">✏️</span></h2>
    </div>

    <form action="" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" readonly>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <input type="text" name="deskripsi" readonly>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Harga Satuan</label>
                <input type="text" name="harga_satuan" readonly>
            </div>
            <div class="form-group">
                <label>Satuan Berat</label>
                <select name="satuan_berat" enable>
                    <option selected>kg</option>
                    <option>gram</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Total Berat</label>
                <input type="text" name="total_berat">
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <input type="text" name="kategori" readonly>
            </div>
        </div>

        <button type="submit" class="btn-confirm">Konfirmasi</button>
        <button type="button" class="btn-cancel">Batalkan</button>
    </form>
</div>

</body>
</html>
