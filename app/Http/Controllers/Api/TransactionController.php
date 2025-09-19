<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BuyTransaction;
use App\Models\BuyCartItem;
use App\Models\UserPoint;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // =====================
    // USER SIDE
    // =====================

    // Buat transaksi baru
    public function create(Request $request)
    {
        $request->validate([
            'total_amount' => 'required|numeric|min:1'
        ]);

        $transaction = BuyTransaction::create([
            'user_id' => $request->user()->id,
            'total_amount' => $request->total_amount,
            'status' => 'pending',
            'transaction_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibuat',
            'data' => $transaction
        ]);
    }

    // Tambah item ke transaksi
    public function addItem(Request $request, $transactionId)
    {
        $request->validate([
            'waste_type_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0'
        ]);

        $transaction = BuyTransaction::findOrFail($transactionId);

        // ❌ hanya cek expired kalau status masih pending
        if ($transaction->status === 'pending' && ! $transaction->isActive()) {
            $transaction->update(['status' => 'cancelled']);

            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah kadaluarsa atau dibatalkan'
            ], 400);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa menambah item ke transaksi yang sudah ' . $transaction->status
            ], 400);
        }

        $subtotal = $request->quantity * $request->price_per_unit;

        $item = BuyCartItem::create([
            'buy_transaction_id' => $transaction->id,
            'waste_type_id' => $request->waste_type_id,
            'quantity' => $request->quantity,
            'price_per_unit' => $request->price_per_unit,
            'subtotal' => $subtotal
        ]);

        $transaction->increment('total_amount', $subtotal);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke transaksi',
            'data' => $item
        ]);
    }

    // Bayar transaksi + kasih point reward
    public function pay($transactionId)
    {
        $transaction = BuyTransaction::with('user')->findOrFail($transactionId);

        // ❌ hanya cek expired kalau status masih pending
        if ($transaction->status === 'pending' && ! $transaction->isActive()) {
            $transaction->update(['status' => 'cancelled']);

            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah kadaluarsa dan dibatalkan'
            ], 400);
        }

        if ($transaction->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah ' . $transaction->status
            ], 400);
        }

        $transaction->update(['status' => 'paid']);

        // Kasih reward point (50 poin per transaksi sukses)
        $user = $transaction->user;
        $pointsEarned = 50;

        $userPoint = UserPoint::firstOrCreate(
            ['user_id' => $user->id],
            ['points' => 0]
        );

        $userPoint->increment('points', $pointsEarned);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dibayar, point reward ditambahkan',
            'data' => [
                'transaction' => $transaction,
                'points_earned' => $pointsEarned,
                'total_points' => $userPoint->points,
            ]
        ]);
    }

    // Cek status transaksi
    public function status($transactionId)
    {
        $transaction = BuyTransaction::with('items')->findOrFail($transactionId);

        if ($transaction->status === 'pending' && ! $transaction->isActive()) {
            $transaction->update(['status' => 'cancelled']);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }

    // List transaksi user
    public function myTransactions(Request $request)
    {
        $transactions = BuyTransaction::with('items')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    // =====================
    // STAFF SIDE
    // =====================

    // List semua transaksi
    public function index()
    {
        $transactions = BuyTransaction::with('items', 'user')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    // Detail 1 transaksi
    public function show($id)
    {
        $transaction = BuyTransaction::with('items', 'user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }
}
