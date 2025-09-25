<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    @include('partials.header')

    <!-- Overlay -->
    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="container">
        <main class="content">
            <!-- Tombol untuk buka/tutup sidebar -->
    <button class="menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>
            <h1>Informasi Pribadi</h1>
            
            {{-- Notifikasi sukses --}}
            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile-pic">
                <img id="profileImage" src="{{ asset('img/ppUser.png') }}" alt="User">

                <!-- Tombol kuas -->
                <button type="button" class="edit-pic" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-paint-brush"></i>
                </button>

                <!-- Input file tersembunyi -->
                <input type="file" id="fileInput" accept="image/*" style="display:none">

                <p class="name">{{ $user->name }}</p>
            </div>
	
	        <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <p>
                    <label>Nama Lengkap:</label><br>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}">
                </p>

                <p>
                    <label>Email (tidak bisa diubah):</label><br>
                    <input type="email" value="{{ $user->email }}" disabled>
                </p>

                <p>
                    <label>Nomor Telepon:</label><br>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                </p>

                <p>
                    <label>Alamat:</label><br>
                    <textarea name="address">{{ old('address', $user->address) }}</textarea>
                </p>

                <p>
                    <label>Tanggal Lahir:</label><br>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
                </p>

                <p>
                    <label>Jenis Kelamin:</label><br>
                    <select name="gender">
                        <option value="">-- Pilih --</option>
                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Tidak ingin memberitahu</option>
                    </select>
                </p>
                
                <p>
                    <label>Password Baru (opsional):</label><br>
                    <input type="password" name="password">
                </p>

                <div class="form-actions">
                    <button type="submit" class="btn save">Simpan Perubahan</button>
                    <button type="reset" class="btn cancel">Buang Perubahan</button>
                </div>
            </form>
        </main>
    </div>

    <script>
        // Preview foto profil
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

        // Toggle sidebar
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.overlay').classList.toggle('show');
        }
    </script>
</body>
</html>
