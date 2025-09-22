<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\BuyTransaction;

class TransactionController extends Controller
{
    // Riwayat transaksi user
    public function index()
    {
        $transactions = BuyTransaction::with(['items.type.category','user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at','desc')
            ->get();

        return view('user.buy.transactions', compact('transactions'));
    }

    // Detail satu transaksi (opsional dipakai)
    public function show($id)
    {
        $trx = BuyTransaction::with(['items.type.category','user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        // Bisa pakai view khusus show. Untuk sederhana, pakai riwayat juga tapi kirim satu item.
        return view('user.buy.transactions', [
            'transactions' => collect([$trx])
        ]);
    }

    // Endpoint untuk polling status
    public function status($id)
    {
        $tx = BuyTransaction::where('id',$id)
              ->where('user_id', Auth::id())
              ->firstOrFail();

        // auto-cancel jika expired dan masih pending
        if ($tx->status === 'pending' && $tx->expired_at && now()->greaterThan($tx->expired_at)) {
            $tx->status = 'cancelled';
            $tx->save();
        }

        return response()->json([
            'id'        => $tx->id,
            'status'    => $tx->status,
            'expired_at'=> optional($tx->expired_at)->toIso8601String(),
        ]);
    }
}
