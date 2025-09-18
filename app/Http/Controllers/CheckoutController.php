<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\WasteType;
use App\Models\WasteStock;
use App\Models\BuyTransaction;
use App\Models\BuyCartItem;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    /**
     * Menerima item yang dipilih dari halaman index (multi-select),
     * validasi stok lalu simpan ke session sebagai 'checkout.items'
     */
    public function prepare(Request $r)
{
    $r->validate([
        'items' => 'required|array',
    ]);

    $posted = $r->input('items'); // items[waste_id][selected], items[waste_id][quantity]
    $items = [];
    $allowed = [1,2,3,4,5,6];

    foreach ($posted as $wasteId => $data) {
        if (!isset($data['selected'])) continue;

        // quantity must be provided and be integer in allowed set
        $qtyRaw = $data['quantity'] ?? null;
        if ($qtyRaw === null) {
            return back()->with('error','Jumlah tidak dipilih untuk produk ID '.$wasteId);
        }

        $qty = (int) $qtyRaw;
        if (!in_array($qty, $allowed, true)) {
            return back()->with('error','Jumlah tidak valid untuk produk ID '.$wasteId.'. Pilih antara 1 sampai 6 kg.');
        }

        $type = WasteType::with('stock','category')->find($wasteId);
        if (!$type) {
            return back()->with('error','Produk tidak ditemukan: ID '.$wasteId);
        }

        $stockQty = (int) ($type->stock->available_weight ?? 0);
        if ($stockQty <= 0) {
            return back()->with('error','Stok habis untuk: '.$type->type_name);
        }
        if ($qty > $stockQty) {
            return back()->with('error','Permintaan melebihi stok untuk: '.$type->type_name);
        }

        $price = $type->price_per_unit ?? 0;
        $subtotal = $price * $qty;

        $items[] = [
            'waste_type_id' => $type->id,
            'type_name' => $type->type_name,
            'category_name' => $type->category->category_name ?? '-',
            'quantity' => $qty,
            'price_per_unit' => $price,
            'subtotal' => $subtotal,
        ];
    }

    if (empty($items)) {
        return back()->with('error','Tidak ada item yang dipilih untuk checkout.');
    }

    $r->session()->put('checkout.items', $items);

    return redirect()->route('checkout.form');
}


    /**
     * Tampilkan form checkout (alamat, metode pembayaran, ringkasan)
     */
    public function show(Request $r)
    {
        $items = $r->session()->get('checkout.items', []);
        if (empty($items)) {
            return redirect()->route('buy-waste.index')->with('error','Keranjang checkout kosong.');
        }

        $subtotal = collect($items)->sum('subtotal');

        // nilai ongkir: pickup gratis, delivery ada biaya
        $shippingPickup = 0;
        $shippingDelivery = 10000;

        $shipping = $shippingPickup;
        $total = $subtotal + $shipping;

        return view('user.buy.checkout', compact('items','subtotal','shipping','total','shippingPickup','shippingDelivery'));
    }

    /**
     * Konfirmasi checkout: validasi lagi, buat transaksi, kurangi stok (lockForUpdate)
     */
    public function confirm(Request $r)
    {
        $r->validate([
            'address_type' => 'required|in:pickup,delivery',
            'payment_method' => 'required|string',
        ]);

        $user = $r->user();
        $items = $r->session()->get('checkout.items', []);
        if (empty($items)) {
            return redirect()->route('buy-waste.index')->with('error','Tidak ada item untuk diproses.');
        }

        $shippingPickup = 0;
        $shippingDelivery = 10000; // dummy
        $shipping = ($r->address_type === 'delivery') ? $shippingDelivery : $shippingPickup;

        DB::beginTransaction();
        try {
            $subtotal = collect($items)->sum('subtotal');
            $total = $subtotal + $shipping;

            $transaction = BuyTransaction::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'paid',
                'transaction_date' => Carbon::now(),
            ]);

            foreach ($items as $it) {
                $stock = WasteStock::where('waste_type_id', $it['waste_type_id'])->lockForUpdate()->first();
                if (!$stock || $stock->available_weight < $it['quantity']) {
                    DB::rollBack();
                    return back()->with('error','Stok tidak cukup untuk '.$it['type_name']);
                }

                $stock->available_weight -= $it['quantity'];
                $stock->save();

                BuyCartItem::create([
                    'buy_transaction_id' => $transaction->id,
                    'waste_type_id' => $it['waste_type_id'],
                    'quantity' => $it['quantity'],
                    'price_per_unit' => $it['price_per_unit'],
                    'subtotal' => $it['subtotal'],
                ]);
            }

            DB::commit();

            $r->session()->forget('checkout.items');

            return redirect()->route('transactions.index')->with('success','Transaksi berhasil (PAID).');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout confirm error: '.$e->getMessage());
            return back()->with('error','Terjadi kesalahan saat memproses transaksi.');
        }
    }
}
