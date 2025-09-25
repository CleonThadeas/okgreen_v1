<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    @include('partials.header')

    <div class="container">
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

                <p class="name">{{ $user->name }}</p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" id="profilForm">
                @csrf
                @method('PATCH')

                <div class="form-row">
                    <div>
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" />
                    </div>
                </div>

                <div>
                    <label>Email (tidak bisa diubah)</label>
                    <input type="email" value="{{ $user->email }}" disabled />
                </div>

                <div>
                    <label>Nomor Telepon</label>
                    <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" />
                </div>

                <div>
                    <label>Alamat</label>
                    <textarea name="address">{{ old('address', $user->address) }}</textarea>
                </div>

                <div>
                    <label>Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $user->birth_date) }}" />
                </div>

                <div>
                    <label>Jenis Kelamin</label>
                    <select name="gender">
                        <option value="">Pilih</option>
                        <option value="male" {{ $user->gender == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ $user->gender == 'female' ? 'selected' : '' }}>Perempuan</option>
                        <option value="other" {{ $user->gender == 'other' ? 'selected' : '' }}>Tidak ingin memberitahu</option>
                    </select>
                </div>

                <div>
                    <label>Password Baru (opsional)</label>
                    <input type="password" name="password">
                </div>

                <div class="form-actions">
                    <button type="reset" class="btn cancel">Buang Perubahan</button>
                    <button type="submit" class="btn save">Simpan Perubahan</button>
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
    </script>
</body>
</html>
