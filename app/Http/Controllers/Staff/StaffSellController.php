<?php
namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\SellWaste;
use App\Models\SellWastePhoto;
use App\Models\UserPoint;
use App\Models\PointHistory;

class StaffSellController extends Controller
{
    public function index()
    {
        $txs = SellWaste::with('user','type','category')->orderBy('created_at','desc')->paginate(20);
        return view('staff.sells.index', compact('txs'));
    }

    public function show($id)
    {
        $tx = SellWaste::with('photos','user','type','category')->findOrFail($id);
        return view('staff.sells.show', compact('tx'));
    }

    public function updateStatus(Request $r, $id)
    {
        $r->validate(['status' => 'required|in:pending,approved,rejected']);
        DB::beginTransaction();
        try {
            $sell = SellWaste::lockForUpdate()->findOrFail($id);
            $old = $sell->status;
            $new = $r->status;

            if ($old !== 'approved' && $new === 'approved') {
                $conversion = config('okgreen.rupiah_per_point', 1000);
                $points = (int) floor($sell->total_price / $conversion);
                $sell->points_awarded = $points;
                $sell->status = 'approved';
                $sell->handled_by_staff_id = Auth::id();
                $sell->handled_at = now();
                $sell->save();

                $up = UserPoint::firstOrCreate(['user_id' => $sell->user_id], ['points' => 0]);
                $up->points += $points;
                $up->save();

                PointHistory::create([
                    'user_id' => $sell->user_id,
                    'source' => 'sell_waste',
                    'reference_id' => $sell->id,
                    'points_change' => $points,
                    'description' => 'Poin dari penjualan sampah #' . $sell->id
                ]);
            } else {
                $sell->status = $new;
                $sell->handled_by_staff_id = Auth::id();
                $sell->handled_at = now();
                $sell->save();
            }

            DB::commit();
            return back()->with('success','Status transaksi diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('StaffSellController@updateStatus error: '.$e->getMessage());
            return back()->with('error','Gagal update status.');
        }
    }
}
