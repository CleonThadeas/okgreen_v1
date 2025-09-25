<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BuyTransaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    // =========================
    // USER SIDE (Flutter)
    // =========================

    // Riwayat transaksi user
    public function myTransactions(Request $request)
    {
        $transactions = BuyTransaction::with(['items.type.category','user'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at','desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    // Detail satu transaksi
    public function show(Request $request, $id)
    {
        $trx = BuyTransaction::with(['items.type.category','user'])
            ->where('user_id', $request->user()->id)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $trx
        ]);
    }

    // Cek status transaksi (polling)
    public function status(Request $request, $id)
    {
        $tx = BuyTransaction::where('id',$id)
              ->where('user_id', $request->user()->id)
              ->firstOrFail();

        // auto-cancel jika expired dan masih pending
        if ($tx->status === 'pending' && $tx->expired_at && now()->greaterThan($tx->expired_at)) {
            $tx->status = 'cancelled';
            $tx->save();
        }

        return response()->json([
            'success'    => true,
            'id'         => $tx->id,
            'status'     => $tx->status,
            'expired_at' => optional($tx->expired_at)->toIso8601String(),
        ]);
    }
}
