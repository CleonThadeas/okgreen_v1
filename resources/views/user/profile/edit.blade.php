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
                    <h2 id="displayName">{{ $user->name }}</h2>
                    <p class="email" id="displayEmail">{{ $user->email }}</p>
                </div>
                <button onclick="openModal()" class="btn-edit"><i class="fas fa-pen"></i> Edit Profil</button>
            </div>

            <div class="profile-details">
                <p><i class="fas fa-phone"></i> <strong>Nomor Telepon:</strong> <span id="displayPhone">{{ $user->phone_number ?? '-' }}</span></p>
                <p><i class="fas fa-map-marker-alt"></i> <strong>Alamat:</strong> <span id="displayAddress">{{ $user->address ?? '-' }}</span></p>
                <p><i class="fas fa-calendar-alt"></i> <strong>Tanggal Lahir:</strong> 
                    <span id="displayDob">{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d M Y') : '-' }}</span>
                </p>
                <p><i class="fas fa-venus-mars"></i> <strong>Jenis Kelamin:</strong> 
                    <span id="displayGender">
                        @if($user->gender == 'laki-laki') Laki-laki
                        @elseif($user->gender == 'perempuan') Perempuan
                        @else Tidak ingin memberitahu
                        @endif
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div id="editModal" class="modal">
        <div class="modal-content" id="modalBox">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3><i class="fas fa-user-edit"></i> Edit Informasi Pribadi</h3>
            <form id="editForm" action="{{ route('profile.update') }}" method="POST">
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
                <select name="gender" id="gender">
                <option value="">Pilih jenis kelamin</option>
                <option value="male" {{ $user->gender == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ $user->gender == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                <option value="other" {{ $user->gender == null ? 'selected' : '' }}>Tidak ingin memberitahu</option>
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
  // ==============================
  // Preview Foto Profil
  // ==============================
  const fileInput = document.getElementById("fileInput");
  const profileImage = document.getElementById("profileImage");

  if (fileInput) {
    fileInput.addEventListener("change", function (event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          profileImage.src = e.target.result;
          profileImage.classList.add("preview-animate");
          setTimeout(() => profileImage.classList.remove("preview-animate"), 500);
        };
        reader.readAsDataURL(file);
      }
    });
  }

  // ==============================
  // Modal Edit
  // ==============================
  const modal = document.getElementById("editModal");
  const modalBox = document.getElementById("modalBox");

  function openModal() {
    modal.style.display = "flex";
    setTimeout(() => modalBox.classList.add("show"), 10);
  }

  function closeModal() {
    modalBox.classList.remove("show");
    setTimeout(() => (modal.style.display = "none"), 200);
  }

  window.onclick = function (event) {
    if (event.target === modal) closeModal();
  };

  // ==============================
  // Sidebar Toggle
  // ==============================
  const sidebarToggle = document.getElementById("sidebarToggle");
  const overlay = document.querySelector(".overlay");
  const sidebar = document.querySelector(".sidebar");
  const body = document.body;

  function toggleSidebar() {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("show");
    body.classList.toggle("sidebar-open");
  }

  if (sidebarToggle) sidebarToggle.addEventListener("click", toggleSidebar);
  if (overlay) overlay.addEventListener("click", toggleSidebar);

  // ==============================
  // Helpers untuk AJAX + Error
  // ==============================
  function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (meta) return meta.getAttribute("content");
    return "{{ csrf_token() }}"; // fallback Blade
  }

  function clearFieldErrors(form) {
    form.querySelectorAll(".error-text").forEach((n) => n.remove());
  }

  function showFieldError(form, fieldName, message) {
    const el = form.querySelector('[name="' + fieldName + '"]');
    if (el) {
      if (
        el.nextElementSibling &&
        el.nextElementSibling.classList &&
        el.nextElementSibling.classList.contains("error-text")
      ) {
        el.nextElementSibling.textContent = message;
        return;
      }
      const err = document.createElement("div");
      err.className = "error-text";
      err.style.color = "#b33";
      err.style.fontSize = "13px";
      err.style.marginTop = "6px";
      err.textContent = message;
      el.insertAdjacentElement("afterend", err);
      return;
    }
    showToast(message, true);
  }

  function showToast(text, isError = false) {
    let toast = document.getElementById("liveToast");
    if (!toast) {
      toast = document.createElement("div");
      toast.id = "liveToast";
      toast.style.position = "fixed";
      toast.style.right = "20px";
      toast.style.bottom = "20px";
      toast.style.padding = "12px 18px";
      toast.style.borderRadius = "8px";
      toast.style.boxShadow = "0 6px 18px rgba(0,0,0,0.2)";
      toast.style.zIndex = 2000;
      toast.style.color = "#fff";
      toast.style.fontWeight = "600";
      toast.style.transition = "opacity .3s ease";
      document.body.appendChild(toast);
    }
    toast.style.background = isError
      ? "linear-gradient(135deg,#c33,#a00)"
      : "linear-gradient(135deg,#28a745,#1e7e34)";
    toast.textContent = text;
    toast.style.opacity = "1";
    clearTimeout(toast._t);
    toast._t = setTimeout(() => {
      toast.style.opacity = "0";
    }, 3500);
  }

  // ==============================
  // AJAX Submit Update Profile
  // ==============================
  const editForm = document.getElementById("editForm");
  if (editForm) {
    editForm.addEventListener("submit", async function (e) {
      e.preventDefault();
      clearFieldErrors(editForm);

      const submitBtn = editForm.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      const formData = new FormData(editForm);

      try {
        const res = await fetch(editForm.action, {
          method: "POST", // form ada _method=PATCH
          headers: {
            "X-CSRF-TOKEN": getCsrfToken(),
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
          },
          body: formData,
        });

        const data = await res.json().catch(() => null);

        if (!res.ok) {
          if (res.status === 422 && data && data.errors) {
            for (const key in data.errors) {
              showFieldError(editForm, key, data.errors[key][0]);
            }
            if (submitBtn) submitBtn.disabled = false;
            return;
          }

          if (res.status === 419) {
            showToast(
              "Session expired (CSRF). Silakan refresh halaman lalu coba lagi.",
              true
            );
            if (submitBtn) submitBtn.disabled = false;
            return;
          }

          const msg =
            data && (data.message || data.error)
              ? data.message || data.error
              : "Terjadi kesalahan server.";
          showToast(msg, true);
          if (submitBtn) submitBtn.disabled = false;
          return;
        }

        const user = data.user ? data.user : data;

        if (user.name)
          document.getElementById("displayName").textContent = user.name;
        if (user.phone_number !== undefined)
          document.getElementById("displayPhone").textContent =
            user.phone_number || "-";
        if (user.address !== undefined)
          document.getElementById("displayAddress").textContent =
            user.address || "-";
        if (user.date_of_birth !== undefined)
          document.getElementById("displayDob").textContent =
            user.date_of_birth || "-";
        if (user.gender !== undefined)
          document.getElementById("displayGender").textContent =
            user.gender || "Tidak ingin memberitahu";

        if (user.avatar_url) {
          const img = document.getElementById("profileImage");
          if (img) img.src = user.avatar_url;
        }

        closeModal();
        showToast("Profil berhasil diperbarui");
      } catch (err) {
        console.error(err);
        showToast("Terjadi kesalahan koneksi. Cek konsol.", true);
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  }
</script>

</body>
</html>
