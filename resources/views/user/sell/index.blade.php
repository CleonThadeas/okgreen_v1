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

            {{-- Form Jual Sampah (pakai data dari BE) --}}
            <form action="{{ route('sell-waste.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <label>Kategori:</label>
                <select name="waste_category_id" id="categorySelect" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                    @endforeach
                </select>

                <label>Jenis Sampah:</label>
                <select name="sell_waste_type_id" id="typeSelect" required>
                    <option value="">-- Pilih Jenis --</option>
                </select>

                <label>Metode:</label>
                <select name="sell_method" required>
                    <option value="drop_point">Drop Point</option>
                    <option value="pickup">Pickup</option>
                </select>

                <label>Berat (Kg/Liter):</label>
                <div class="berat-container">
                    <input type="number" step="0.01" name="weight" required>
                </div>

                <label>Foto Sampah:</label>
                <div class="upload-box">
                    <input type="file" name="photo[]" multiple required>
                </div>

                <label>Deskripsi:</label>
                <textarea name="description" rows="3"></textarea>

                <button type="submit" class="jual-btn">Kirim Permintaan Jual</button>
            </form>
        </div>

        <div class="jual-container">
            <h2>Riwayat Penjualan Saya</h2>
            <table border="1" cellpadding="8" width="100%">
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
                        <td>{{ ucfirst($s->status) }}</td>
                        <td>{{ $s->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="8" align="center">Belum ada penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    {{-- Script AJAX untuk load jenis sesuai kategori --}}
    <script>
    document.getElementById('categorySelect').addEventListener('change', function(){
        let catId = this.value;
        let typeSelect = document.getElementById('typeSelect');
        typeSelect.innerHTML = '<option value="">Loading...</option>';
        if(!catId) return;

        fetch('/sell-waste/types/' + catId)
            .then(res => res.json())
            .then(data => {
                typeSelect.innerHTML = '<option value="">-- Pilih Jenis --</option>';
                data.forEach(t => {
                    typeSelect.innerHTML += `<option value="${t.id}">${t.type_name} (Poin/Kg: ${t.points_per_kg})</option>`;
                });
            })
            .catch(err => {
                console.error(err);
                typeSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
            });
    });
    </script>
</body>
</html>
