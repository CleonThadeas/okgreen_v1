<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk['nama'] ?? 'Detail Produk' }}</title>
    <link rel="stylesheet" href="{{ asset('css/jualbarang.css') }}?v={{ time() }}">
</head>
<body>

    {{-- Include header --}}
    @include('partials.header')

    <div class="jual-container">
        <h2>Form Jual Sampah</h2>

        <form id="jualForm">
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
            <!-- Popup Notifikasi -->
            <div id="notifPopup" class="popup-overlay">
            <div class="popup-card">
                <div class="popup-header">
                <img src="{{ asset('img/logo-greenleaf.png') }}" alt="Ilustrasi" />
                </div>
                <div class="popup-body">
                <h3>Sukses!</h3>
                <p>Pesan Anda telah berhasil terkirim.<br>Kami akan segera menghubungi Anda kembali.</p>
                </div>
                <div class="popup-footer">
                <button type="button" onclick="closePopup()">Ignore</button>
                <button class="btn-accept">Get Notification</button>
                </div>
            </div>
            </div>
        </form>
    </div>

    <script>
        // Preview nama file yang diupload
        document.getElementById('foto').addEventListener('change', function() {
            let fileName = this.files[0] ? this.files[0].name : "Upload foto";
            document.getElementById('fileName').textContent = fileName;
        });

        // Handler ketika form disubmit
        document.getElementById('jualForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // TODO: Tambahkan fetch/ajax untuk kirim data ke backend
        });

        // Preview nama file yang diupload
            document.getElementById('foto').addEventListener('change', function() {
                let fileName = this.files[0] ? this.files[0].name : "Upload foto";
                document.getElementById('fileName').textContent = fileName;
            });

            // Munculkan popup setelah submit form
            document.getElementById('jualForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Supaya tidak reload halaman
                document.getElementById('notifPopup').style.display = "flex";
                
                // Reset form setelah submit
                this.reset();
                document.getElementById('fileName').textContent = "Upload foto"; // reset tulisan upload
            });

            // Tutup popup
            function closePopup() {
                document.getElementById('notifPopup').style.display = "none";
            }
    </script>
    <script>// Popup tampil smooth
document.getElementById('jualForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const popup = document.getElementById('notifPopup');
    popup.classList.add("active"); // tambahkan class animasi

    // Reset form
    this.reset();
    document.getElementById('fileName').textContent = "Upload foto";
});

// Tutup popup dengan animasi smooth
function closePopup() {
    const popup = document.getElementById('notifPopup');
    popup.classList.remove("active");
}
</script>
</body>
</html>
