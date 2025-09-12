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
     * Menerima item dari halaman detail produk atau halaman list.
     * - Jika dari halaman detail: kirim `product_id`, `size`, `qty`.
     * - Jika dari halaman list: kirim `items[]`.
     */
    public function prepare(Request $r)
    {
        // ==== Jika checkout dari halaman detail produk ====
        if ($r->has('product_id')) {
            $r->validate([
                'product_id' => 'required|integer|exists:waste_types,id',
                'size'       => 'required',
                'qty'        => 'required|integer|min:1',
            ]);

            $produk = WasteType::with('stock','category')->find($r->product_id);
            if (!$produk) {
                return $this->handleError($r, 'Produk tidak ditemukan.');
            }

            $stockQty = (int) ($produk->stock->available_weight ?? 0);
            if ($stockQty <= 0) {
                return $this->handleError($r, 'Stok habis untuk produk ini.');
            }
            if ($r->qty > $stockQty) {
                return $this->handleError($r, 'Jumlah melebihi stok yang tersedia.');
            }

            $price = $produk->price_per_unit ?? 0;
            $subtotal = $price * $r->qty;

            $items = [[
                'waste_type_id'   => $produk->id,
                'type_name'       => $produk->type_name,
                'category_name'   => $produk->category->category_name ?? '-',
                'quantity'        => $r->qty,
                'price_per_unit'  => $price,
                'subtotal'        => $subtotal,
                'size'            => $r->size,
            ]];

            // Simpan ke session
            $r->session()->put('checkout.items', $items);

            // Jika request via AJAX, balikan JSON
            if ($r->ajax()) {
                return response()->json([
                    'message' => 'Item berhasil ditambahkan ke checkout',
                    'redirect_url' => route('checkout.form'),
                ]);
            }

            return redirect()->route('checkout.form');
        }

        // ==== Jika checkout dari halaman list produk (multi select) ====
        $r->validate([
            'items' => 'required|array',
        ]);

        $posted = $r->input('items');
        $items = [];

        foreach ($posted as $wasteId => $data) {
            if (!isset($data['selected'])) continue;

            // Ambil qty
            $qtyRaw = $data['quantity'] ?? null;
            if ($qtyRaw === null) {
                return $this->handleError($r, 'Jumlah tidak dipilih untuk produk ID '.$wasteId);
            }

            $qty = (int) $qtyRaw;

            // Validasi jumlah
            if ($qty < 1) {
                return $this->handleError($r, 'Jumlah minimal 1 untuk produk ID '.$wasteId);
            }

            $type = WasteType::with('stock','category')->find($wasteId);
            if (!$type) {
                return $this->handleError($r, 'Produk tidak ditemukan: ID '.$wasteId);
            }

            $stockQty = (int) ($type->stock->available_weight ?? 0);
            if ($stockQty <= 0) {
                return $this->handleError($r, 'Stok habis untuk: '.$type->type_name);
            }
            if ($qty > $stockQty) {
                return $this->handleError($r, 'Permintaan melebihi stok untuk: '.$type->type_name);
            }

            $price = $type->price_per_unit ?? 0;
            $subtotal = $price * $qty;

            $items[] = [
                'waste_type_id'  => $type->id,
                'type_name'      => $type->type_name,
                'category_name'  => $type->category->category_name ?? '-',
                'quantity'       => $qty,
                'price_per_unit' => $price,
                'subtotal'       => $subtotal,
            ];
        }

        if (empty($items)) {
            return $this->handleError($r, 'Tidak ada item yang dipilih untuk checkout.');
        }

        $r->session()->put('checkout.items', $items);

        if ($r->ajax()) {
            return response()->json([
                'message' => 'Item berhasil ditambahkan ke checkout',
                'redirect_url' => route('checkout.form'),
            ]);
        }

        return redirect()->route('checkout.form');
    }

    /**
     * Helper untuk handle error agar bisa dipakai AJAX & normal redirect.
     */
    private function handleError(Request $r, $message)
    {
        if ($r->ajax()) {
            return response()->json(['error' => $message], 400);
        }
        return back()->with('error', $message);
    }

    /**
     * Tampilkan form checkout (alamat, metode pembayaran, ringkasan)
     */
    public function show(Request $r)
    {
        $items = $r->session()->get('checkout.items', []);
        if (empty($items)) {
            return redirect()->route('buy.index')->with('error', 'Keranjang checkout kosong.');
        }

        $subtotal = collect($items)->sum('subtotal');

        // Ongkir
        $shippingPickup = 0;
        $shippingDelivery = 10000;
        $shipping = $shippingPickup;
        $total = $subtotal + $shipping;

        // ==== Tambahan Pickup Locations (STATIC) ====
        $pickupLocations = [
            ['id' => 1, 'name' => 'Kantor OKGreen Pusat', 'address' => 'Jl. Asia Afrika No.10, Bandung'],
            ['id' => 2, 'name' => 'OKGreen Cabang Barat', 'address' => 'Jl. Sukawarna No.77, Bandung'],
            ['id' => 3, 'name' => 'OKGreen Cabang Timur', 'address' => 'Jl. Terusan Pasirkoja No.07, Bandung'],
        ];

        // ==== Address milik user yang login ====
        $addresses = $r->user()->addresses ?? collect();

        return view('user.buy.checkout', compact(
            'items',
            'subtotal',
            'shipping',
            'total',
            'shippingPickup',
            'shippingDelivery',
            'pickupLocations',
            'addresses'
        ));
    }

    /**
     * Konfirmasi checkout: validasi lagi, buat transaksi, kurangi stok
     */
    public function confirm(Request $r)
    {
        $r->validate([
            'address_type'   => 'required|in:pickup,delivery',
            'payment_method' => 'required|string',
        ]);

        $user = $r->user();
        $items = $r->session()->get('checkout.items', []);
        if (empty($items)) {
            return redirect()->route('buy.index')->with('error', 'Tidak ada item untuk diproses.');
        }

        $shippingPickup = 0;
        $shippingDelivery = 10000;
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
                    return back()->with('error', 'Stok tidak cukup untuk '.$it['type_name']);
                }

                // Kurangi stok
                $stock->available_weight -= $it['quantity'];
                $stock->save();

                // Simpan detail item
                BuyCartItem::create([
                    'buy_transaction_id' => $transaction->id,
                    'waste_type_id'      => $it['waste_type_id'],
                    'quantity'           => $it['quantity'],
                    'price_per_unit'     => $it['price_per_unit'],
                    'subtotal'           => $it['subtotal'],
                ]);
            }

            DB::commit();

            $r->session()->forget('checkout.items');

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil (PAID).');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout confirm error: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses transaksi.');
        }
    }
}
