@extends('layouts.app')

@section('title', 'Beli Sampah')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Sampah Tersedia</h2>

    @if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif
    @if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif

    <form id="multiCheckoutForm" action="{{ route('checkout.prepare') }}" method="POST">
      @csrf

      <table border="1" cellpadding="10" cellspacing="0" width="100%">
          <thead>
              <tr>
                  <th>Pilih</th>
                  <th>Foto</th>
                  <th>Kategori</th>
                  <th>Jenis Sampah</th>
                  <th>Harga / Kg</th>
                  <th>Stok Tersedia (Kg)</th>
                  <th>Jumlah (Kg)</th>
              </tr>
          </thead>
          <tbody>
              @forelse($wastes as $waste)
                  @php
                    $id = $waste->id;
                    $stock = (int) ($waste->stock->available_weight ?? 0);
                    $price = $waste->price_per_unit ?? 0;
                  @endphp
                  <tr data-id="{{ $id }}">
                      <td style="text-align:center;">
                        @if($stock <= 0)
                          <input type="checkbox" disabled>
                        @else
                          <input class="select-item" type="checkbox" name="items[{{ $id }}][selected]" value="1" id="sel_{{ $id }}">
                        @endif
                      </td>

                      <td>
                        @if($waste->photo)
                          <img src="{{ asset('storage/'.$waste->photo) }}" width="80">
                        @else
                          <span>Tidak ada foto</span>
                        @endif
                      </td>

                      <td>{{ $waste->category->category_name ?? '-' }}</td>
                      <td>{{ $waste->type_name }}</td>
                      <td>Rp {{ number_format($price, 0, ',', '.') }}</td>

                      <td>
                        {{ $stock }}
                        @if($stock <= 0)
                          <span style="color:red; font-weight:bold; margin-left:8px;">(Stok Habis)</span>
                        @endif
                      </td>

                      <td>
                        <!-- quantity options 1..6 -->
                        <div class="qty-boxes" style="display:flex; gap:8px; flex-wrap:wrap;">
                          @for($q=1;$q<=6;$q++)
                            @php $disabled = ($stock <= 0 || $q > $stock) ? 'disabled' : ''; @endphp
                            <label class="qty-box" style="border:1px solid #ddd; padding:6px 10px; border-radius:6px; cursor:pointer; user-select:none; {{ $disabled ? 'opacity:0.5; cursor:not-allowed;' : '' }}">
                              <input
                                type="radio"
                                name="items[{{ $id }}][quantity]"
                                value="{{ $q }}"
                                class="qty-radio"
                                style="display:none;"
                                {{ $disabled ? 'disabled' : '' }}
                              >
                              {{ $q }} kg
                            </label>
                          @endfor
                        </div>

                        <div class="muted" style="font-size:0.9rem; margin-top:6px;">
                          Pilih 1 - 6 kg (bulat)
                        </div>
                      </td>
                  </tr>
              @empty
                  <tr>
                      <td colspan="7" class="text-center">Belum ada sampah tersedia.</td>
                  </tr>
              @endforelse
          </tbody>
      </table>

      <div style="margin-top:12px;">
        <button id="btnCheckoutNow" type="submit" class="btn">Checkout Sekarang</button>
      </div>
    </form>
</div>

<style>
.qty-box.selected { border-color:#0b7a4a; background:#ecfff3; font-weight:700; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
  document.querySelectorAll('.qty-box').forEach(function(lbl){
    lbl.addEventListener('click', function(){
      if (lbl.querySelector('input[type=radio]').disabled) return;
      const radio = lbl.querySelector('input[type=radio]');
      radio.checked = true;
      const container = lbl.closest('.qty-boxes');
      container.querySelectorAll('.qty-box').forEach(l => l.classList.remove('selected'));
      lbl.classList.add('selected');
      const row = lbl.closest('tr');
      const chk = row.querySelector('.select-item');
      if (chk && !chk.checked) chk.checked = true;
    });
  });

  document.querySelectorAll('.select-item').forEach(function(chk){
    chk.addEventListener('change', function(){
      const row = chk.closest('tr');
      const container = row.querySelector('.qty-boxes');
      if (!chk.checked) {
        container.querySelectorAll('.qty-box').forEach(l => l.classList.remove('selected'));
        const r = container.querySelector('input[type=radio]:checked');
        if(r) r.checked = false;
      } else {
        const any = container.querySelector('input[type=radio]:checked');
        if(!any) {
          const firstAvail = container.querySelector('input[type=radio]:not(:disabled)');
          if(firstAvail) {
            firstAvail.checked = true;
            firstAvail.closest('.qty-box').classList.add('selected');
          }
        }
      }
    });
  });

  document.getElementById('multiCheckoutForm').addEventListener('submit', function(e){
    const rows = document.querySelectorAll('tbody tr');
    let anySelected = false;

    for (let row of rows) {
      const chk = row.querySelector('.select-item');
      const stock = parseInt(row.cells[5].innerText) || 0;
      if (chk && chk.checked) {
        anySelected = true;
        const qRadio = row.querySelector('input[type=radio]:checked');
        if (!qRadio) {
          alert('Silakan pilih jumlah (1-6 kg) untuk produk: ' + row.cells[3].innerText);
          e.preventDefault(); return false;
        }
        const q = parseInt(qRadio.value, 10);
        if (!Number.isInteger(q) || q < 1 || q > 6) {
          alert('Jumlah harus bulat 1-6 untuk produk: ' + row.cells[3].innerText);
          e.preventDefault(); return false;
        }
        if (q > stock) {
          alert('Stok tidak cukup untuk produk: ' + row.cells[3].innerText +
                '\\nStok: ' + stock + ' kg. Anda meminta: ' + q + ' kg.');
          e.preventDefault(); return false;
        }
      }
    }
    if (!anySelected) {
      alert('Pilih minimal satu produk untuk checkout.');
      e.preventDefault(); return false;
    }
  });

  document.querySelectorAll('tbody tr').forEach(function(row){
    const stock = parseInt(row.cells[5].innerText) || 0;
    if (stock <= 0) {
      row.querySelectorAll('.qty-box').forEach(l => { l.classList.add('disabled'); l.style.opacity = 0.5; });
    } else {
      row.querySelectorAll('.qty-box').forEach(function(l){
        const radio = l.querySelector('input[type=radio]');
        const val = parseInt(radio.value);
        if (val > stock) {
          radio.disabled = true;
          l.style.opacity = 0.5;
          l.style.cursor = 'not-allowed';
        }
      });
    }
  });
});
</script>
@endsection
