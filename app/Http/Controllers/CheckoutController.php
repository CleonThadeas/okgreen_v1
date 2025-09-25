<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BuyTransaction;
use App\Models\BuyCartItem;
use App\Models\WasteType;
use App\Models\WasteStock;

class CheckoutController extends Controller
{
    /**
     * Form checkout (ringkasan keranjang dari session 'buy_cart')
     */
    public function show(Request $request)
    {
        $cart = session('buy_cart'); 
        if (!$cart || !is_array($cart) || count($cart) === 0) {
            return redirect()->route('buy-waste.index')->with('error','Keranjang kosong.');
        }

        $items = [];
        $subtotal = 0;
        foreach ($cart as $row) {
            $type = WasteType::with('category')->find($row['waste_type_id']);
            if (!$type) continue;

            $qty = (float) $row['quantity'];
            $price = (float) $type->price_per_unit;
            $sub   = $price * $qty;
            $subtotal += $sub;

            $items[] = [
                'waste_type_id'  => $type->id,
                'type_name'      => $type->type_name,
                'category_name'  => optional($type->category)->category_name,
                'quantity'       => $qty,
                'price_per_unit' => $price,
                'subtotal'       => $sub,
            ];
        }

        $shippingPickup   = 0;
        $shippingDelivery = 10000; 

        return view('user.buy.checkout', [
            'items'            => $items,
            'subtotal'         => $subtotal,
            'shipping'         => 0,
            'total'            => $subtotal,
            'shippingPickup'   => $shippingPickup,
            'shippingDelivery' => $shippingDelivery,
        ]);
    }

    /**
     * Simpan keranjang dari halaman index
     */
    public function prepare(Request $request)
    {
        $posted = $request->input('items', []);

        $items = [];
        foreach ($posted as $wasteId => $data) {
            // format checkbox style: items[ID][selected], items[ID][quantity]
            if (is_array($data)) {
                if (!isset($data['selected'])) continue;
                $qty = (float)($data['quantity'] ?? 0);
                if ($qty <= 0) continue;
                $items[] = ['waste_type_id' => $wasteId, 'quantity' => $qty];
            }
            // format langsung array numeric: items[0][waste_type_id], items[0][quantity]
            elseif (isset($posted['waste_type_id'])) {
                $items = $posted;
                break;
            }
        }

        if (empty($items)) {
            return back()->with('error','Tidak ada item dipilih untuk checkout.');
        }

        session(['buy_cart' => $items]);
        return redirect()->route('checkout.form');
    }

    /**
     * Buat transaksi pending + expired_at + QR dummy
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'address_type'   => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:transfer,qris,cod',
            'receiver_name'  => 'nullable|string|max:150',
            'address'        => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:30',
        ]);

        $cart = session('buy_cart');
        if (!$cart || count($cart) === 0) {
            return redirect()->route('buy-waste.index')->with('error','Keranjang kosong.');
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsBuild = [];

            foreach ($cart as $row) {
                $type = WasteType::findOrFail($row['waste_type_id']);
                $qty  = (float) $row['quantity'];
                $price= (float) $type->price_per_unit;
                $sub  = $price * $qty;
                $subtotal += $sub;

                $itemsBuild[] = compact('type','qty','price','sub');
            }

            $shipping_cost = $request->address_type === 'delivery' ? 10000 : 0;
            $total = $subtotal + $shipping_cost;

            $tx = new BuyTransaction();
            $tx->user_id         = Auth::id();
            $tx->total_amount    = $total;
            $tx->status          = 'pending';
            $tx->transaction_date= Carbon::now();
            $tx->payment_method  = $request->input('payment_method','qris');
            $tx->shipping_method = $request->address_type;
            $tx->receiver_name   = $request->receiver_name;
            $tx->address         = $request->address;
            $tx->phone           = $request->phone;
            $tx->shipping_cost   = $shipping_cost;
            $tx->expired_at      = Carbon::now()->addMinutes(10);

            // QR dummy
            $tx->qr_text = 'okgreen-demo-static-qr-payload-'.$total;
            $tx->save();

            foreach ($itemsBuild as $it) {
                BuyCartItem::create([
                    'buy_transaction_id' => $tx->id,
                    'waste_type_id'      => $it['type']->id,
                    'quantity'           => $it['qty'],
                    'price_per_unit'     => $it['price'],
                    'subtotal'           => $it['sub'],
                ]);

                $stock = WasteStock::where('waste_type_id',$it['type']->id)->lockForUpdate()->first();
                if ($stock) {
                    if ($stock->available_weight < $it['qty']) {
                        DB::rollBack();
                        return back()->with('error','Stok tidak cukup untuk '.$it['type']->type_name);
                    }
                    $stock->available_weight -= $it['qty'];
                    $stock->save();
                }
            }

            DB::commit();
            session()->forget('buy_cart');

            return redirect()->route('checkout.qr', $tx->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','Gagal membuat transaksi: '.$e->getMessage());
        }
    }

    /**
     * Halaman QR
     */
    public function qrView($id)
    {
        $tx = BuyTransaction::where('id',$id)
              ->where('user_id', Auth::id())
              ->firstOrFail();

        if (!$tx->expired_at) {
            $tx->expired_at = Carbon::now()->addMinutes(10);
            $tx->save();
        }

        return view('user.buy.checkout-qr', compact('tx'));
    }
}
