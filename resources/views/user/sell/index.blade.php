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
                            <button class="btn-accept" onclick="closePopup()">OK</button>
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

        {{-- RIWAYAT --}}
        <div class="jual-container animate-card">
    <h2>Riwayat Penjualan Saya</h2>
    <div class="table-wrapper">
        <table class="riwayat-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kategori</th>
                    <th>Jenis</th>
                    <th>Berat</th>
                    <th>Poin / Kg</th>
                    <th>Total Poin</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sells as $s)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $s->category->category_name ?? '-' }}</td>
                        <td>{{ $s->sellType->type_name ?? '-' }}</td>
                        <td>{{ $s->weight_kg }}</td>
                        <td>{{ $s->price_per_kg }}</td>
                        <td>{{ $s->points_awarded ?? 0 }}</td>
                        <td>
                            <span class="badge 
                                {{ $s->status == 'pending' ? 'badge-pending' : '' }}
                                {{ $s->status == 'approved' ? 'badge-success' : '' }}
                                {{ $s->status == 'rejected' ? 'badge-danger' : '' }}">
                                {{ ucfirst($s->status) }}
                            </span>
                        </td>
                        <td>{{ $s->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="no-data">Belum ada penjualan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

    {{-- Script --}}
    <script>
        // Load jenis sampah by kategori
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

// Upload box
function triggerFile() {
    document.getElementById('fileInput').click();
}

// Preview Foto
const fileInput = document.getElementById('fileInput');
const previewContainer = document.getElementById('previewContainer');

if(fileInput){
    fileInput.addEventListener('change', function(){
        previewContainer.innerHTML = "";
        const files = this.files;
        if(files){
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement("img");
                    img.src = e.target.result;
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    });
}

// Popup close
function closePopup() {
    document.getElementById('popup').classList.remove('active');
}

    </script>
</body>
</html>
