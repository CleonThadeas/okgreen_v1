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
                        @if($stock > 0)
                          <input 
                            type="number" 
                            name="items[{{ $id }}][quantity]" 
                            min="1" 
                            max="{{ $stock }}" 
                            placeholder="0"
                            value=""
                            oninput="validasiJumlah(this, {{ $stock }})"
                            style="width:80px; padding:4px;"
                          > Kg
                        @else
                          <span style="color:red">Tidak tersedia</span>
                        @endif
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

<script>
function validasiJumlah(input, stock) {
    let val = parseInt(input.value);

    // Jika kosong → jangan ubah
    if (isNaN(val)) return;

    // Hanya angka bulat
    if (!Number.isInteger(val)) {
        input.value = Math.floor(val);
    }

    // Tidak boleh < 1
    if (val < 1) {
        input.value = "";
        return;
    }

    // Tidak boleh lebih besar dari stok
    if (val > stock) {
        input.value = stock;
    }

    // Auto centang checkbox produk
    const row = input.closest('tr');
    const chk = row.querySelector('.select-item');
    if (chk && !chk.checked) {
        chk.checked = true;
    }
}

document.getElementById('multiCheckoutForm').addEventListener('submit', function(e){
    let anySelected = false;

    document.querySelectorAll('tbody tr').forEach(function(row){
        const chk = row.querySelector('.select-item');
        if (chk && chk.checked) {
            anySelected = true;
            const qtyInput = row.querySelector('input[type=number]');
            if (qtyInput) {
                let q = parseInt(qtyInput.value);
                let stock = parseInt(row.cells[5].innerText) || 0;

                if (isNaN(q) || q < 1) {
                    alert('Jumlah harus minimal 1 untuk produk: ' + row.cells[3].innerText);
                    e.preventDefault(); return false;
                }
                if (q > stock) {
                    alert('Stok tidak cukup untuk produk: ' + row.cells[3].innerText +
                          '\nStok: ' + stock + ' kg. Anda meminta: ' + q + ' kg.');
                    qtyInput.value = stock;
                    e.preventDefault(); return false;
                }
            }
        }
    });

    if (!anySelected) {
        alert('Pilih minimal satu produk untuk checkout.');
        e.preventDefault(); return false;
    }
});
</script>
@endsection
