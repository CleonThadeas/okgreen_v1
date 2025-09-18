<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Jual Sampah')</title>
    <link rel="stylesheet" href="{{ asset('css/jualbarang.css') }}?v={{ time() }}">
    <style>
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    @include('partials.header')

    <div class="container">
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

        {{-- Form Jual Sampah --}}
        <form action="{{ route('sell-waste.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <p>
                <label>Kategori:</label>
                <select name="waste_category_id" id="categorySelect" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->category_name }}</option>
                    @endforeach
                </select>
            </p>

            <p>
                <label>Jenis Sampah:</label>
                <select name="sell_waste_type_id" id="typeSelect" required>
                    <option value="">-- Pilih Jenis --</option>
                </select>
            </p>

            <p>
                <label>Metode:</label>
                <select name="sell_method" required>
                    <option value="drop_point">Drop Point</option>
                    <option value="pickup">Pickup</option>
                </select>
            </p>

            <p>
                <label>Berat (Kg/Liter):</label>
                <input type="number" step="0.01" name="weight" required>
            </p>

            <p>
                <label>Foto Sampah:</label>
                <input type="file" name="photo[]" multiple>
            </p>

            <p>
                <label>Deskripsi:</label><br>
                <textarea name="description" rows="3" cols="40"></textarea>
            </p>

            <button type="submit">Kirim Permintaan Jual</button>
        </form>

        <hr>
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
