<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jual Sampah</title>
    <link rel="stylesheet" href="{{ asset('css/jualbarang.css') }}?v={{ time() }}">
</head>
<body>
    @include('partials.header')

    <main>
        {{-- FORM JUAL --}}
        <div class="jual-container animate-card">
            <h2>Form Jual Sampah</h2>

            {{-- Pesan sukses pakai popup --}}
            @if(session('success'))
                <div class="popup-overlay active" id="popup">
                    <div class="popup-card">
                        <div class="popup-header">
                            <img src="{{ asset('img/logo1.png') }}" alt="Success">
                        </div>
                        <div class="popup-body">
                            <h3>Berhasil!</h3>
                            <p>{{ session('success') }}</p>
                        </div>
                        <div class="popup-footer">
                            <button class="btn-accept" onclick="closePopup()">Abaikan</button>
                            <a href="{{ route('history.sell') }}" class="btn-cek">Cek Riwayat</a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Pesan error --}}
            @if(session('error'))
                <div class="alert-error animate-fade">{{ session('error') }}</div>
            @endif

            {{-- Form --}}
            <form action="{{ route('sell-waste.store') }}" method="POST" enctype="multipart/form-data" class="animate-fade">
                @csrf

                <label>Kategori: <span class="required">*</span></label>
                <select name="waste_category_id" id="categorySelect" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}" {{ old('waste_category_id') == $c->id ? 'selected' : '' }}>
                            {{ $c->category_name }}
                        </option>
                    @endforeach
                </select>

                <label>Jenis Sampah: <span class="required">*</span></label>
                <select name="sell_waste_type_id" id="typeSelect" required>
                    <option value="">-- Pilih Jenis --</option>
                </select>

                <label>Metode: <span class="required">*</span></label>
                <select name="sell_method" required>
                    <option value="drop_point" {{ old('sell_method')=='drop_point'?'selected':'' }}>Drop Point</option>
                    <option value="pickup" {{ old('sell_method')=='pickup'?'selected':'' }}>Pickup</option>
                </select>

                <label>Berat (Kg/Liter): <span class="required">*</span></label>
                <div class="berat-container">
                    <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" required>
                </div>

                <label>Foto Sampah: <span class="required">*</span></label>
                <div class="upload-box" onclick="triggerFile()">
                    <p>Klik untuk unggah foto</p>
                    <input type="file" name="photo[]" id="fileInput" multiple hidden required>
                </div>

                {{-- Preview foto --}}
                <div id="previewContainer" class="preview-container"></div>

                <label>Deskripsi (opsional):</label>
                <textarea name="description" rows="3">{{ old('description') }}</textarea>

                <button type="submit" class="jual-btn">Kirim Permintaan Jual</button>
            </form>
        </div>
    </main>

    <!-- Modal Preview -->
    <div id="imgModal" class="img-modal">
    <span class="close-modal" onclick="closeImgModal()">&times;</span>
    <img class="modal-content-img" id="imgPreviewLarge">
    </div>

    {{-- Script --}}
<script>
    // === Load jenis sampah by kategori ===
    const categorySelect = document.getElementById('categorySelect');
    const typeSelect = document.getElementById('typeSelect');
    const oldType = document.querySelector('input[name="sell_waste_type_id"]')?.value || "";

    function loadTypes(catId, selectedType = null) {
        typeSelect.innerHTML = '<option value="">Loading...</option>';
        if(!catId) return;

        fetch('/sell-waste/types/' + catId)
            .then(res => res.json())
            .then(data => {
                typeSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
                data.forEach(t => {
                    const selected = (t.id == selectedType) ? 'selected' : '';
                    typeSelect.innerHTML += `<option value="${t.id}" ${selected}>${t.type_name} (Poin/Kg: ${t.points_per_kg})</option>`;
                });
            })
            .catch(() => {
                typeSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            });
    }
    if(categorySelect){
        categorySelect.addEventListener('change', () => loadTypes(categorySelect.value));
        if(categorySelect.value) loadTypes(categorySelect.value, oldType);
    }

    // === Upload box trigger ===
    function triggerFile() {
        document.getElementById('fileInput').click();
    }

    // === Preview Foto + Hapus Foto + Zoom Modal ===
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    let selectedFiles = []; // simpan file di array

    if(fileInput){
        fileInput.addEventListener('change', function(){
            selectedFiles = Array.from(this.files);
            renderPreviews();
        });
    }

    function renderPreviews() {
        previewContainer.innerHTML = "";

        selectedFiles.forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement("div");
                wrapper.classList.add("preview-item");

                const img = document.createElement("img");
                img.src = e.target.result;
                img.onclick = () => openImgModal(img.src);

                const removeBtn = document.createElement("span");
                removeBtn.innerHTML = "&times;";
                removeBtn.classList.add("remove-btn");
                removeBtn.onclick = () => {
                    selectedFiles.splice(index, 1); // hapus dari array
                    updateFileInput();
                    renderPreviews();
                };

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                previewContainer.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }

    // === Popup close ===
    function closePopup() {
        document.getElementById('popup').classList.remove('active');
    }

    // === Modal Zoom Gambar ===
    function openImgModal(src) {
        const modal = document.getElementById("imgModal");
        const modalImg = document.getElementById("imgPreviewLarge");
        modal.style.display = "block";
        modalImg.src = src;
    }

    function closeImgModal() {
        document.getElementById("imgModal").style.display = "none";
    }
</script>

</body>
</html>
