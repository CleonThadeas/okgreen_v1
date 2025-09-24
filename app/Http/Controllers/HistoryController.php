<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPointHistory;
use App\Models\Transaction;
use App\Models\SellWaste;

class HistoryController extends Controller
{
    /**
     * Riwayat poin user
     */
    public function points()
    {
        $user = Auth::user();   // ✅ benar
        $userId = $user->id;    // ✅ ambil id dengan benar

        $histories = UserPointHistory::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalPoints = UserPointHistory::where('user_id', $userId)
            ->sum('points_change');

        return view('user.points.historypoints', compact('histories', 'totalPoints'));
    }

    /**
     * Riwayat pembelian
     */
    public function buy()
    {
        $userId = Auth::id();   // ✅ lebih singkat
        $transactions = Transaction::where('user_id', $userId)
            ->with('items.type.category')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.buy.transactions', compact('transactions'));
    }

    /**
     * Riwayat penjualan
     */
    public function sell()
    {
        $userId = Auth::id();
        $sells = SellWaste::where('user_id', $userId)
            ->with(['category', 'sellType'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.profile.historysell', compact('sells'));
    }
}
