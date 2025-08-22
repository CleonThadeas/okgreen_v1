@extends('layouts.app')

@section('title','Checkout')

@section('content')
<div class="container">
  <h2>Checkout</h2>

  @if(session('error')) <div style="color:red">{{ session('error') }}</div> @endif
  @if(session('success')) <div style="color:green">{{ session('success') }}</div> @endif

  <form action="{{ route('checkout.confirm') }}" method="POST" id="checkoutForm">
    @csrf

    <div class="card" style="margin-bottom:12px;">
      <h3>Alamat</h3>
      <label><input type="radio" name="address_type" value="pickup" checked> Ambil di Tempat (Gratis)</label>
      <label style="margin-left:12px;"><input type="radio" name="address_type" value="delivery"> Diantar (Berbayar)</label>

      <div id="deliveryBlock" style="margin-top:12px; display:none;">
        <!-- dummy address fields -->
        <div>
          <label>Nama Penerima</label>
          <input type="text" name="receiver_name" value="Nama Dummy">
        </div>
        <div>
          <label>Alamat Lengkap</label>
          <input type="text" name="address" value="Jalan Contoh No.123">
        </div>
        <div>
          <label>Nomor HP</label>
          <input type="text" name="phone" value="081234567890">
        </div>
      </div>
    </div>

    <div class="card" style="margin-bottom:12px;">
      <h3>Metode Pembayaran</h3>
      <label><input type="radio" name="payment_method" value="transfer" checked> Transfer Bank (dummy)</label>
      <label style="margin-left:12px;"><input type="radio" name="payment_method" value="cod"> COD (dummy)</label>
    </div>

    <div class="card">
      <h3>Ringkasan Pesanan</h3>
      <table border="1" cellpadding="8" width="100%">
        <thead><tr><th>Jenis</th><th>Qty (Kg)</th><th>Harga/kg</th><th>Subtotal</th></tr></thead>
        <tbody>
          @foreach($items as $it)
            <tr>
              <td>{{ $it['type_name'] }}</td>
              <td>{{ $it['quantity'] }}</td>
              <td>Rp {{ number_format($it['price_per_unit'],0,',','.') }}</td>
              <td>Rp {{ number_format($it['subtotal'],0,',','.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>

      <p style="margin-top:10px;">Subtotal: <strong id="subtotalText">Rp {{ number_format($subtotal,0,',','.') }}</strong></p>
      <p>Ongkir: <strong id="shippingText">Rp {{ number_format($shipping,0,',','.') }}</strong></p>
      <p>Total: <strong id="totalText">Rp {{ number_format($total,0,',','.') }}</strong></p>

      <div style="margin-top:12px;">
        <button type="submit" class="btn">Konfirmasi & Bayar (Simulasi Paid)</button>
        <a href="{{ route('buy-waste.index') }}" style="margin-left:12px;">Kembali Pilih Produk</a>
      </div>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
  // server-supplied values
  const shippingPickup = Number(@json($shippingPickup));      // e.g. 0
  const shippingDelivery = Number(@json($shippingDelivery));  // e.g. 10000
  const subtotal = Number(@json($subtotal));                  // subtotal in number

  const radios = document.querySelectorAll('input[name="address_type"]');
  const deliveryBlock = document.getElementById('deliveryBlock');
  const shippingText = document.getElementById('shippingText');
  const totalText = document.getElementById('totalText');

  function formatRupiah(num) {
    return num.toLocaleString('id-ID');
  }

  function updateTotals() {
    const selected = document.querySelector('input[name="address_type"]:checked').value;
    const shipping = (selected === 'delivery') ? shippingDelivery : shippingPickup;
    const total = subtotal + shipping;

    shippingText.innerText = 'Rp ' + formatRupiah(shipping);
    totalText.innerText = 'Rp ' + formatRupiah(total);
  }

  function toggleDeliveryBlock() {
    const selected = document.querySelector('input[name="address_type"]:checked').value;
    if (selected === 'delivery') {
      deliveryBlock.style.display = 'block';
    } else {
      deliveryBlock.style.display = 'none';
    }
    updateTotals();
  }

  radios.forEach(r => r.addEventListener('change', toggleDeliveryBlock));
  // init
  toggleDeliveryBlock();
});
</script>
@endsection
