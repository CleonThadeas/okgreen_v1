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

        $posted = $r->input('items');
        $items = [];

        foreach ($posted as $wasteId => $data) {
            if (!isset($data['selected'])) continue;

            $qtyRaw = $data['quantity'] ?? null;
            if ($qtyRaw === null) {
                return back()->with('error','Jumlah tidak dipilih untuk produk ID '.$wasteId);
            }

            $qty = (int) $qtyRaw;

            if ($qty < 1) {
                return back()->with('error','Jumlah minimal 1 untuk produk ID '.$wasteId);
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
            return redirect()->route('buy.index')->with('error','Keranjang checkout kosong.');
        }

        $subtotal = collect($items)->sum('subtotal');

        $shippingPickup = 0;
        $shippingDelivery = 10000;

        $shipping = $shippingPickup;
        $total = $subtotal + $shipping;

        return view('user.buy.checkout', compact('items','subtotal','shipping','total','shippingPickup','shippingDelivery'));
    }

    /**
     * Konfirmasi checkout: validasi lagi, buat transaksi, kurangi stok.
     * Jika payment_method == 'qris_static' => status pending, expired_at = now + 10 menit, simpan qr_text.
     * Jika metode lain => langsung 'paid' (seperti behavior lama).
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
            return redirect()->route('buy.index')->with('error','Tidak ada item untuk diproses.');
        }

        $shippingPickup = 0;
        $shippingDelivery = 10000;
        $shipping = ($r->address_type === 'delivery') ? $shippingDelivery : $shippingPickup;

        DB::beginTransaction();
        try {
            $subtotal = collect($items)->sum('subtotal');
            $total = $subtotal + $shipping;

            // Tentukan status awal berdasarkan payment_method
            $paymentMethod = $r->payment_method;
            $isQrisStatic = ($paymentMethod === 'qris_static' || $paymentMethod === 'qris');

            $txData = [
                'user_id' => $user->id,
                'total_amount' => $total,
                'transaction_date' => Carbon::now(),
            ];

            if ($isQrisStatic) {
                $txData['status'] = 'pending';
                $txData['expired_at'] = Carbon::now()->addMinutes(10);
                // qr_text bisa disimpan dari config/env atau per-transaksi (jika QR berbeda)
                $txData['qr_text'] = config('qris.static_payload', env('QRIS_STATIC_PAYLOAD', null));
            } else {
                $txData['status'] = 'paid';
                $txData['handled_at'] = Carbon::now();
            }

            $transaction = BuyTransaction::create($txData);

            // Kurangi stok dan buat BuyCartItem — ini men-reserve stok segera.
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

            // Hapus session keranjang
            $r->session()->forget('checkout.items');

            if ($isQrisStatic) {
                // Redirect ke halaman QR + countdown
                return redirect()->route('checkout.qr', ['id' => $transaction->id])
                    ->with('success',"Transaksi dibuat. Silakan bayar menggunakan QRIS dalam 10 menit.");
            } else {
                return redirect()->route('transactions.index')->with('success','Transaksi berhasil (PAID).');
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout confirm error: '.$e->getMessage());
            return back()->with('error','Terjadi kesalahan saat memproses transaksi.');
        }
    }

    /**
     * Tampilkan halaman QR + countdown untuk transaksi QRIS statis
     */
    public function qrView(Request $r, $id)
    {
        $tx = BuyTransaction::findOrFail($id);

        // pastikan pemilik transaksi yang melihat (atau staff)
        if ($tx->user_id !== $r->user()->id && !$r->user()->hasRole('staff')) {
            abort(403, 'Unauthorized');
        }

        // jika bukan pending, arahkan sesuai status
        if ($tx->status !== 'pending') {
            return redirect()->route('transactions.show', $tx->id)
                ->with('info','Transaksi ini sudah berstatus: '.$tx->status);
        }

        return view('user.buy.checkout_qr', compact('tx'));
    }

    /**
     * Endpoint JSON untuk polling status transaksi
     */
    public function status(Request $r, $id)
    {
        $tx = BuyTransaction::findOrFail($id);

        // cek permission (pemilik atau staff)
        if ($tx->user_id !== $r->user()->id && !$r->user()->hasRole('staff')) {
            return response()->json(['error' => 'unauthorized'], 403);
        }

        return response()->json([
            'status' => $tx->status,
            'handled_by_staff_id' => $tx->handled_by_staff_id,
            'handled_at' => $tx->handled_at ? $tx->handled_at->toDateTimeString() : null,
            'expired_at' => $tx->expired_at ? $tx->expired_at->toDateTimeString() : null,
        ]);
    }
}
