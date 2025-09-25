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

            {{-- Tampilkan data user --}}
            <div class="profile-pic">
                <img id="profileImage" src="{{ asset('img/ppUser.png') }}" alt="User">
                <button type="button" class="edit-pic" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-paint-brush"></i>
                </button>
                <input type="file" id="fileInput" accept="image/*" style="display:none">
                <p class="name">{{ $user->name }}</p>
            </div>

            <div style="border:1px solid #ddd; padding:20px; border-radius:8px; background:#f9f9f9; margin-top:15px;">
                <p><strong>Nama Lengkap:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }} <small style="color:gray;">(tidak dapat diubah)</small></p>
                <p><strong>Nomor Telepon:</strong> {{ $user->phone_number ?? '-' }}</p>
                <p><strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
                <p><strong>Tanggal Lahir:</strong> 
                    {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d M Y') : '-' }}
                </p>
                <p><strong>Jenis Kelamin:</strong> 
                    @if($user->gender == 'laki-laki') Laki-laki
                    @elseif($user->gender == 'perempuan') Perempuan
                    @else Tidak ingin memberitahu
                    @endif
                </p>
            </div>

            {{-- Tombol Edit --}}
            <button onclick="openModal()" style="margin-top:15px; background:#4CAF50; color:white; border:none; padding:8px 15px;">
                Edit Profil
            </button>

        </main>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.6);">
        <div style="background:white; padding:20px; border-radius:8px; width:500px; margin:50px auto; position:relative;">
            <h3>Edit Informasi Pribadi</h3>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <p>
                    <label>Nama Lengkap:</label><br>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}">
                </p>

                <p>
                    <label>Tanggal Lahir:</label><br>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">
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
                    <label>Jenis Kelamin:</label><br>
                    <select name="gender">
                        <option value="">-- Pilih --</option>
                        <option value="laki-laki" {{ $user->gender == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ $user->gender == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Tidak ingin memberitahu</option>
                    </select>
                </p>

                <p>
                    <label>Password Baru (opsional):</label><br>
                    <input type="password" name="password">
                </p>

                <div style="margin-top:10px; display:flex; justify-content:space-between;">
                    <button type="button" onclick="closeModal()" style="background:#ccc; border:none; padding:8px 15px;">Batal</button>
                    <button type="submit" style="background:#4CAF50; color:white; border:none; padding:8px 15px;">Simpan</button>
                </div>
            </form>
        </div>
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

        // Modal edit
        function openModal(){
            document.getElementById('editModal').style.display='block';
        }
        function closeModal(){
            document.getElementById('editModal').style.display='none';
        }
    </script>
</body>
</html>
