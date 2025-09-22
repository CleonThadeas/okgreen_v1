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

class CartController extends Controller
{
    public function index(Request $request)
    {
        $cart = $request->session()->get('cart', []);
        return view('user.buy.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'waste_type_id' => 'required|exists:waste_types,id',
            'quantity' => 'required|numeric|min:0.01'
        ]);

        $type = WasteType::with('stock')->findOrFail($request->waste_type_id);
        $price = $type->price_per_unit ?? 0;
        $quantity = floatval($request->quantity);
        $subtotal = $price * $quantity;

        // Optional: cek stok sebelum ditambahkan ke cart (tidak wajib, karena stok final dicek saat checkout)
        if (($type->stock->available_weight ?? 0) < $quantity) {
            return back()->with('error', 'Stok tidak cukup untuk '.$type->type_name);
        }

        $cart = $request->session()->get('cart', []);
        // gabungkan jika sudah ada item sama
        $found = false;
        foreach ($cart as &$item) {
            if ($item['waste_type_id'] == $type->id) {
                $item['quantity'] = $item['quantity'] + $quantity;
                $item['subtotal'] = $item['price_per_unit'] * $item['quantity'];
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = [
                'waste_type_id' => $type->id,
                'type_name' => $type->type_name,
                'quantity' => $quantity,
                'price_per_unit' => $price,
                'subtotal' => $subtotal
            ];
        }
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Item ditambahkan ke keranjang.');
    }

    public function checkout(Request $request)
    {
        $user = $request->user();
        $cart = $request->session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();
        try {
            $total = collect($cart)->sum('subtotal');

            $transaction = BuyTransaction::create([
                'user_id' => $user->id,
                'total_amount' => $total,
                'status' => 'paid', // simulasi langsung paid
                'transaction_date' => Carbon::now()
            ]);

            foreach ($cart as $item) {
                // lock stok untuk mencegah oversell
                $stock = WasteStock::where('waste_type_id', $item['waste_type_id'])->lockForUpdate()->first();
                if (!$stock || $stock->available_weight < $item['quantity']) {
                    DB::rollBack();
                    return back()->with('error', 'Stok tidak cukup untuk: '.$item['type_name']);
                }
                // kurangi stok
                $stock->available_weight -= $item['quantity'];
                $stock->save();

                // simpan item
                BuyCartItem::create([
                    'buy_transaction_id' => $transaction->id,
                    'waste_type_id' => $item['waste_type_id'],
                    'quantity' => $item['quantity'],
                    'price_per_unit' => $item['price_per_unit'],
                    'subtotal' => $item['subtotal']
                ]);
            }

            DB::commit();
            // bersihkan cart session
            $request->session()->forget('cart');

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil (PAID).');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Checkout error: '.$e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat checkout.');
        }
    }
}
