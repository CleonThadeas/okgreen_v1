<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WasteType;
use App\Models\WasteStock;
use App\Models\BuyTransaction;
use App\Models\BuyCartItem;
use Carbon\Carbon;

class CartController extends Controller
{
    // Tambah item ke keranjang (langsung simpan ke DB sementara atau kirim balik ke Flutter)
    public function add(Request $request)
    {
        $request->validate([
            'waste_type_id' => 'required|exists:waste_types,id',
            'quantity'      => 'required|numeric|min:0.01',
        ]);

        $type = WasteType::with('stock')->findOrFail($request->waste_type_id);

        if (($type->stock->available_weight ?? 0) < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup untuk '.$type->type_name,
            ], 422);
        }

        $item = [
            'waste_type_id'  => $type->id,
            'type_name'      => $type->type_name,
            'quantity'       => $request->quantity,
            'price_per_unit' => $type->price_per_unit,
            'subtotal'       => $type->price_per_unit * $request->quantity,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke cart (client-side).',
            'item'    => $item,
        ]);
    }

    // Checkout langsung buat transaksi
    public function checkout(Request $request)
    {
        $request->validate([
            'items'          => 'required|array|min:1',
            'items.*.waste_type_id' => 'required|exists:waste_types,id',
            'items.*.quantity'      => 'required|numeric|min:0.01',
        ]);

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $itemsBuild = [];

            foreach ($request->items as $row) {
                $type  = WasteType::findOrFail($row['waste_type_id']);
                $qty   = (float) $row['quantity'];
                $price = (float) $type->price_per_unit;
                $sub   = $price * $qty;
                $subtotal += $sub;

                $itemsBuild[] = compact('type', 'qty', 'price', 'sub');
            }

            $transaction = BuyTransaction::create([
                'user_id'          => $request->user()->id,
                'total_amount'     => $subtotal,
                'status'           => 'pending',
                'transaction_date' => Carbon::now(),
                'expired_at'       => Carbon::now()->addMinutes(10),
                'qr_text'          => 'okgreen-demo-static-qr-'.$subtotal,
            ]);

            foreach ($itemsBuild as $it) {
                $stock = WasteStock::where('waste_type_id', $it['type']->id)
                                   ->lockForUpdate()
                                   ->first();

                if (!$stock || $stock->available_weight < $it['qty']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak cukup untuk '.$it['type']->type_name,
                    ], 422);
                }

                $stock->available_weight -= $it['qty'];
                $stock->save();

                BuyCartItem::create([
                    'buy_transaction_id' => $transaction->id,
                    'waste_type_id'      => $it['type']->id,
                    'quantity'           => $it['qty'],
                    'price_per_unit'     => $it['price'],
                    'subtotal'           => $it['sub'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat',
                'transaction' => $transaction->load('items.wasteType'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Checkout gagal: '.$e->getMessage(),
            ], 500);
        }
    }

    // List transaksi user
    public function myTransactions(Request $request)
    {
        $transactions = BuyTransaction::where('user_id', $request->user()->id)
            ->with('items.wasteType')
            ->orderBy('transaction_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }

    // Detail transaksi
    public function show($id, Request $request)
    {
        $tx = BuyTransaction::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->with('items.wasteType')
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'transaction' => $tx,
        ]);
    }
}
