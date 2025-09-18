<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BuyTransaction;

class TransactionController extends Controller
{
    /**
     * Tampilkan riwayat transaksi user beserta detail item tiap transaksi
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil transaksi user, urut terbaru; eager load items dan tipe sampah
        $transactions = BuyTransaction::with(['items.type'])
            ->where('user_id', $user->id)
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('user.buy.transactions', compact('transactions'));
    }
}
