<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk['nama'] ?? 'Detail Produk' }}</title>
    <link rel="stylesheet" href="{{ asset('css/jualbarang.css') }}?v={{ time() }}">

    <style>
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    {{-- Include header --}}
    @include('partials.header')

    <div class="jual-container">
        <h2>Form Jual Sampah</h2>

        {{-- Pesan sukses --}}
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        {{-- Pesan error --}}
        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="jualForm" method="POST" action="{{ route('sell-waste.index') }}" enctype="multipart/form-data">
            @csrf

            <!-- Kategori -->
            <label for="kategori">Kategori</label>
            <select id="kategori" name="kategori" required>
                <option value="">Pilih Kategori Sampah</option>
                <option value="organik">Organik</option>
                <option value="anorganik">Anorganik</option>
                <option value="elektronik">Elektronik</option>
            </select>

            <!-- Jenis -->
            <label for="jenis">Jenis</label>
            <select id="jenis" name="jenis" required>
                <option value="">Pilih Jenis Sampah</option>
                <option value="plastik">Plastik</option>
                <option value="kertas">Kertas</option>
                <option value="logam">Logam</option>
                <option value="kaca">Kaca</option>
            </select>

            <!-- Berat -->
            <label for="berat">Berat</label>
            <div class="berat-container">
                <input 
                    type="number" 
                    id="berat" 
                    name="berat" 
                    placeholder="Masukkan berat" 
                    min="0" 
                    required
                >
                <select id="satuan" name="satuan" required>
                    <option value="kg">Kg</option>
                    <option value="gram">Gram</option>
                    <option value="ton">Ton</option>
                </select>
            </div>

            <!-- Deskripsi -->
            <label for="deskripsi">Deskripsi</label>
            <textarea 
                id="deskripsi" 
                name="deskripsi" 
                placeholder="Masukan Deskripsi (Opsional)"
            ></textarea>

            <!-- Upload Foto -->
            <label for="foto">Foto</label>
            <div class="upload-box" onclick="document.getElementById('foto').click()">
                <p id="fileName">Upload foto</p>
                <input 
                    type="file" 
                    id="foto" 
                    name="foto" 
                    accept="image/*" 
                    style="display:none;"
                >
            </div>

            <!-- Tombol Jual -->
            <button type="submit" class="jual-btn">Jual</button>
        </form>
    </div>

    <script>
        // Preview nama file yang diupload
        document.getElementById('foto').addEventListener('change', function() {
            let fileName = this.files[0] ? this.files[0].name : "Upload foto";
            document.getElementById('fileName').textContent = fileName;
        });
    </script>
</body>
</html>
