<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
     @include('partials.header')
    <div class="container">
        
        <!-- Main Content -->
        <main class="content">
            <h1>Informasi Pribadi</h1>

            <div class="profile-pic">
            <img id="profileImage" src="{{ asset('img/ppUser.png') }}" alt="User">
            
            <!-- Tombol kuas -->
            <button type="button" class="edit-pic" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-paint-brush"></i>
            </button>
            
            <!-- Input file tersembunyi -->
            <input type="file" id="fileInput" accept="image/*" style="display:none">
            
            <p class="name">Pengguna</p>
        </div>


            <form action="#" method="POST" id="profilForm">
                <div class="form-row">
                    <div>
                        <label>Nama Depan</label>
                        <input type="text" name="nama_depan" />
                    </div>
                    <div>
                        <label>Nama Belakang</label>
                        <input type="text" name="nama_belakang" />
                    </div>
                </div>

                <div>
                    <label>Email</label>
                    <input type="email" name="email" />
                </div>

                <div>
                    <label>Nomor Telepon</label>
                    <input type="text" name="telepon" />
                </div>

                <div>
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" />
                </div>

                <div>
                    <label>Jenis Kelamin</label>
                    <select name="gender">
                        <option value="">Pilih</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn cancel">Buang Perubahan</button>
                    <button type="submit" class="btn save">Simpan Perubahan</button>
                </div>
            </form>
        </main>
    </div>

    <script>
        document.getElementById("profilForm").addEventListener("submit", function(e) {
            e.preventDefault();
            alert("Perubahan berhasil disimpan!");
        });
    </script>
    <script>
    document.getElementById("fileInput").addEventListener("change", function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById("profileImage").src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
</script>

</body>
</html>
