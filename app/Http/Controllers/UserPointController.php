<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserPoint;
use App\Models\PointHistory;

class UserPointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    /**
     * Tampilkan total poin user & riwayat
     */
    public function index()
    {
        $user = Auth::user();

        // ambil total poin user
        $userPoints = UserPoint::where('user_id', auth()->id())->value('points') ?? 0;
        // ambil riwayat
        $histories = PointHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.points.index', compact('user', 'userPoints', 'histories'));
    }
}
