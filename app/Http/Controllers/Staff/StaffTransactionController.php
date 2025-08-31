<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BuyTransaction;
use App\Models\BuyCartItem;
use App\Models\WasteStock;

class StaffTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // ganti dengan 'auth:staff' jika pakai guard staff
    }

    public function index()
    {
        $txs = BuyTransaction::orderBy('created_at','desc')->paginate(20);
        return view('staff.sell_requests.index', compact('txs'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,paid,canceling']);

        $tx = BuyTransaction::findOrFail($id);

        DB::beginTransaction();
        try {
            $oldStatus = $tx->status;
            $newStatus = $request->input('status');

            $tx->status = $newStatus;
            $tx->handled_by_staff_id = Auth::id(); // <--- aman
            $tx->handled_at = Carbon::now();
            $tx->save();

            if ($newStatus === 'canceling' && $oldStatus !== 'canceling') {
                $items = BuyCartItem::where('buy_transaction_id', $tx->id)->get();
                foreach ($items as $it) {
                    $stock = WasteStock::where('waste_type_id', $it->waste_type_id)->lockForUpdate()->first();
                    if ($stock) {
                        $stock->available_weight += $it->quantity;
                        $stock->save();
                    }
                }
            }

            DB::commit();
            return back()->with('success','Status transaksi diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error','Gagal update status: '.$e->getMessage());
        }
    }
}
