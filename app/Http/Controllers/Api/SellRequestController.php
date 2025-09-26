<?php

namespace App\Http\Controllers\Api\Staff;

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
        $this->middleware('auth:staff'); // pastikan guard staff dipakai di sanctum
    }

    /**
     * GET daftar permintaan jual
     */
    public function index()
    {
        $requests = SellWaste::with(['user','sellType','category'])
            ->orderBy('created_at','desc')
            ->paginate(20);

        return response()->json($requests);
    }

    /**
     * GET detail permintaan jual
     */
    public function show($id)
    {
        $req = SellWaste::with(['user','sellType','category','photos'])->find($id);

        if (!$req) {
            return response()->json(['message' => 'Permintaan jual tidak ditemukan'], 404);
        }

        return response()->json($req);
    }

    /**
     * PUT update status (approve/cancel)
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
                $points = (int) ($sell->weight_kg * $sell->sellType->points_per_kg);

                $sell->status = 'approved';
                $sell->points_awarded = $points;
                $sell->handled_by_staff_id = Auth::id();
                $sell->handled_at = now();
                $sell->save();

                $up = UserPoint::firstOrCreate(
                    ['user_id' => $sell->user_id],
                    ['points' => 0]
                );
                $up->points += $points;
                $up->save();

                PointHistory::create([
                    'user_id'        => $sell->user_id,
                    'source'         => 'sell_waste',
                    'reference_id'   => $sell->id,
                    'points_change'  => $points,
                    'description'    => 'Poin dari penjualan sampah #' . $sell->id
                ]);

                DB::commit();
                return response()->json([
                    'message' => 'Permintaan jual berhasil disetujui',
                    'data'    => $sell
                ], 200);

            } elseif ($r->status === 'canceled') {
                $sell->status = 'canceled';
                $sell->points_awarded = 0;
                $sell->handled_by_staff_id = Auth::id();
                $sell->handled_at = now();
                $sell->save();

                DB::commit();
                return response()->json([
                    'message' => 'Permintaan jual berhasil dibatalkan',
                    'data'    => $sell
                ], 200);
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('SellRequestApiController@updateStatus error: '.$e->getMessage());

            return response()->json([
                'message' => 'Gagal mengupdate status',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
