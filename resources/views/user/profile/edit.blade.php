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

    <div class="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Cover -->
        <div class="profile-cover">
            <img src="{{ asset('img/1.png') }}" alt="Cover">
        </div>

        <!-- Sidebar Toggle Button -->
        <button id="sidebarToggle" class="sidebar-toggle">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Profile Card -->
        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-avatar">
                    <img id="profileImage" src="{{ asset('img/ppUser.png') }}" alt="User">
                    <button type="button" class="edit-pic" onclick="document.getElementById('fileInput').click()">
                        <i class="fas fa-camera"></i>
                    </button>
                    <input type="file" id="fileInput" accept="image/*" style="display:none">
                </div>
                <div class="profile-info">
                    <h2>{{ $user->name }}</h2>
                    <p class="email">{{ $user->email }}</p>
                </div>
                <button onclick="openModal()" class="btn-edit"><i class="fas fa-pen"></i> Edit Profil</button>
            </div>

            <div class="profile-details">
                <p><i class="fas fa-phone"></i> <strong>Nomor Telepon:</strong> {{ $user->phone_number ?? '-' }}</p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Alamat:</strong> {{ $user->address ?? '-' }}</p>
                <p><i class="fas fa-calendar-alt"></i> <strong>Tanggal Lahir:</strong> 
                    {{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d M Y') : '-' }}
                </p>
                <p><i class="fas fa-venus-mars"></i> <strong>Jenis Kelamin:</strong> 
                    @if($user->gender == 'laki-laki') Laki-laki
                    @elseif($user->gender == 'perempuan') Perempuan
                    @else Tidak ingin memberitahu
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="modal">
        <div class="modal-content" id="modalBox">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3><i class="fas fa-user-edit"></i> Edit Informasi Pribadi</h3>
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <label>Nama Lengkap:</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}">

                <label>Tanggal Lahir:</label>
                <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth) }}">

                <label>Nomor Telepon:</label>
                <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">

                <label>Alamat:</label>
                <textarea name="address">{{ old('address', $user->address) }}</textarea>

                <label>Jenis Kelamin:</label>
                <select name="gender">
                    <option value="">-- Pilih --</option>
                    <option value="laki-laki" {{ $user->gender == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="perempuan" {{ $user->gender == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Tidak ingin memberitahu</option>
                </select>

                <label>Password Baru (opsional):</label>
                <input type="password" name="password">

                <div class="form-actions">
                    <button type="button" class="btn cancel" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn save">Simpan</button>
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

        // Modal edit
        const modal = document.getElementById("editModal");
        const modalBox = document.getElementById("modalBox");

        function openModal(){
            modal.style.display = 'flex';
            setTimeout(() => modalBox.classList.add("show"), 10);
        }
        function closeModal(){
            modalBox.classList.remove("show");
            setTimeout(() => modal.style.display = 'none', 200);
        }

        // Klik luar modal
        window.onclick = function(event) {
            if (event.target === modal) closeModal();
        }

    
    // Sidebar toggle
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.querySelector('.overlay');
        const body = document.body;

        sidebar.classList.toggle('active');
        overlay.classList.toggle('show');
        body.classList.toggle('sidebar-open'); // ini penting biar CSS auto-hide jalan
    }

    // Tombol toggle
    document.getElementById('sidebarToggle').addEventListener('click', toggleSidebar);

    // Klik overlay untuk nutup sidebar
    document.querySelector('.overlay').addEventListener('click', toggleSidebar);
    </script>
</body>
</html>
