<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPointHistory;
use App\Models\Transaction;
use App\Models\SellWaste;
use App\Models\PointHistory;
use App\Models\UserPoint;  

class HistoryController extends Controller
{
    /**
     * Riwayat poin user
     */
    public function points()
    {
        $user = Auth::user();

        // total poin user
        $totalPoints = UserPoint::where('user_id', $user->id)->value('points') ?? 0;

        // history poin (pagination)
        $histories = PointHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.points.historypoints', compact('totalPoints', 'histories'));
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
