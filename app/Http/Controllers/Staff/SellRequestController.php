<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\SellWaste;
use App\Models\UserPoint;
use App\Models\PointHistory;

class SellRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');
    }

    /**
     * Tampilkan daftar permintaan jual
     */
    public function index()
    {
        $requests = SellWaste::with(['user','sellType','category'])
            ->orderBy('created_at','desc')
            ->paginate(20);

        return view('staff.sell_requests.index', compact('requests'));
    }

    /**
     * Detail permintaan jual
     */
    public function show($id)
    {
        $req = SellWaste::with(['user','sellType','category','photos'])->findOrFail($id);
        return view('staff.sell_requests.show', compact('req'));
    }

    /**
     * Update status (approve/cancel)
     */
    public function updateStatus(Request $r, $id)
    {
        $r->validate([
            'status' => 'required|in:approved,canceled'
        ]);

        DB::beginTransaction();
        try {
            $sell = SellWaste::lockForUpdate()->findOrFail($id);

            if ($r->status === 'approved') {
                // hitung poin berdasarkan berat x poin per kg
                $points = (int) ($sell->weight_kg * $sell->sellType->points_per_kg);

                $sell->status = 'approved';
                $sell->points_awarded = $points;
                $sell->handled_by_staff_id = Auth::id();
                $sell->handled_at = now();
                $sell->save();

                // Tambahkan ke saldo user
                $up = UserPoint::firstOrCreate(
                    ['user_id' => $sell->user_id],
                    ['points' => 0]
                );
                $up->points += $points;
                $up->save();

                // Catat riwayat poin
                PointHistory::create([
                    'user_id'        => $sell->user_id,
                    'source'         => 'sell_waste',
                    'reference_id'   => $sell->id,
                    'points_change'  => $points,
                    'description'    => 'Poin dari penjualan sampah #' . $sell->id
                ]);

                DB::commit();
                return redirect()
                    ->route('staff.sell_requests.index')
                    ->with('success', 'Permintaan jual #' . $sell->id . ' berhasil disetujui dan poin ditambahkan.');

            } elseif ($r->status === 'canceled') {
                $sell->status = 'canceled';
                $sell->points_awarded = 0;
                $sell->handled_by_staff_id = Auth::id();
                $sell->handled_at = now();
                $sell->save();

                DB::commit();
                return redirect()
                    ->route('staff.sell_requests.index')
                    ->with('success', 'Permintaan jual #' . $sell->id . ' dibatalkan.');
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('SellRequestController@updateStatus error: '.$e->getMessage());
            return redirect()
                ->route('staff.sell_requests.index')
                ->with('error','Gagal mengupdate status: '.$e->getMessage());
        }
    }
}