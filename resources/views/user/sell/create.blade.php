@extends('layouts.app')

@section('title','Form Jual Sampah')

@section('content')
<div class="container" style="max-width:720px;">
  <h2 class="mb-4">Form Jual Sampah</h2>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data" id="sellForm">
    @csrf

    <div class="mb-3">
      <label for="waste_category_id" class="form-label">Kategori</label>
      <select name="waste_category_id" id="waste_category_id" class="form-select" required>
        <option value="">Pilih Kategori Sampah</option>
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ old('waste_category_id') == $c->id ? 'selected' : '' }}>
            {{ $c->category_name }}
          </option>
        @endforeach
      </select>
      @error('waste_category_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="waste_type_id" class="form-label">Jenis</label>
      <select name="waste_type_id" id="waste_type_id" class="form-select" required>
        <option value="">Pilih Jenis Sampah</option>
        @foreach($types as $t)
          <option value="{{ $t->id }}" data-price="{{ $t->price_per_unit ?? 0 }}"
            {{ old('waste_type_id') == $t->id ? 'selected' : '' }}>
            {{ $t->type_name }}
          </option>
        @endforeach
      </select>
      @error('waste_type_id') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Menjual Sampah</label>
      <div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="sell_method" id="method_drop" value="drop_point" checked>
          <label class="form-check-label" for="method_drop">Simpan Ke Mini Drop Point</label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="sell_method" id="method_pickup" value="pickup">
          <label class="form-check-label" for="method_pickup">Supir Ke Lokasimu</label>
        </div>
      </div>
      @error('sell_method') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="weight" class="form-label">Berat</label>
      <select name="weight" id="weight" class="form-select" required>
        <option value="">Pilih Berat Sampah</option>
        <option value="1">1 Kg</option>
        <option value="2">2 Kg</option>
        <option value="5">5 Kg</option>
        <option value="10">10 Kg</option>
        <option value="20">20 Kg</option>
        <option value="50">50 Kg</option>
      </select>
      @error('weight') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Deskripsi (opsional)</label>
      <textarea name="description" id="description" class="form-control" rows="3" placeholder="Masukan deskripsi (opsional)">{{ old('description') }}</textarea>
      @error('description') <div class="text-danger small">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
      <label class="form-label">Foto (minimal 1, bisa multiple)</label>
      <input type="file" name="photo[]" id="photo" class="form-control" accept="image/*" multiple required>
      @error('photo') <div class="text-danger small">{{ $message }}</div> @enderror
      <div id="preview" class="mt-2 d-flex gap-2 flex-wrap"></div>
    </div>

    <div class="mb-3 p-3 bg-light rounded">
      <div><strong>Ringkasan</strong></div>
      <div>Harga / Kg: <span id="pricePerKg">Rp 0</span></div>
      <div>Berat: <span id="previewWeight">-</span> Kg</div>
      <div>Total estimasi: <span id="previewTotal">Rp 0</span></div>
      <div>Poin estimasi: <span id="previewPoints">0</span></div>
      <small class="text-muted">Poin konversi: 1 poin = Rp {{ number_format(config('okgreen.rupiah_per_point',1000),0,',','.') }}</small>
    </div>

    <button type="submit" class="btn btn-success w-100">Jual</button>
  </form>
</div>

<script>
  // preview images
  document.getElementById('photo').addEventListener('change', function(e){
    const preview = document.getElementById('preview');
    preview.innerHTML = '';
    const files = e.target.files;
    if(!files) return;
    for(let i=0;i<files.length;i++){
      const f = files[i];
      if(!f.type.startsWith('image/')) continue;
      const url = URL.createObjectURL(f);
      const img = document.createElement('img');
      img.src = url;
      img.style.height = '90px';
      img.style.objectFit = 'cover';
      img.style.borderRadius = '6px';
      preview.appendChild(img);
    }
  });

  // price/points preview logic
  const typesSelect = document.getElementById('waste_type_id');
  const weightSelect = document.getElementById('weight');
  const pricePerKgEl = document.getElementById('pricePerKg');
  const previewWeightEl = document.getElementById('previewWeight');
  const previewTotalEl = document.getElementById('previewTotal');
  const previewPointsEl = document.getElementById('previewPoints');
  const rupiahPerPoint = {{ config('okgreen.rupiah_per_point', 1000) }};

  function formatRupiah(n){
    return n.toLocaleString('id-ID');
  }

  function updatePreview(){
    const opt = typesSelect.selectedOptions[0];
    const price = opt ? parseFloat(opt.dataset.price || 0) : 0;
    const weight = parseFloat(weightSelect.value || 0);
    const total = price * (isNaN(weight)?0:weight);
    const points = Math.floor(total / rupiahPerPoint);
    pricePerKgEl.innerText = 'Rp ' + formatRupiah(price);
    previewWeightEl.innerText = isNaN(weight)?'-':weight;
    previewTotalEl.innerText = 'Rp ' + formatRupiah(total);
    previewPointsEl.innerText = points;
  }

  typesSelect.addEventListener('change', updatePreview);
  weightSelect.addEventListener('change', updatePreview);
  document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection
