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
use App\Models\WasteType;

class StaffTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff');// atau 'auth:staff' jika pakai guard custom
    }

    public function index()
{
    // Ambil semua transaksi terbaru
    $txs = BuyTransaction::with('user')
        ->orderBy('created_at','desc')
        ->paginate(20);

    return view('staff.transactions.index', compact('txs'));
}
public function show($id)
{
    $tx = BuyTransaction::with('items.type.category','user')->findOrFail($id);
    return view('staff.transactions.show', compact('tx'));
}

    /**
     * Update status transaksi (staff)
     */
    public function updateStatus(Request $request, $id)
{
    $request->validate(['status' => 'required|in:pending,paid,cancelled']);

    $tx = BuyTransaction::with('items')->findOrFail($id);

    DB::beginTransaction();
    try {
        $oldStatus = $tx->status;
        $newStatus = $request->status;

        if ($newStatus === 'paid' && $oldStatus !== 'paid') {
            // cek stok setiap item
            foreach ($tx->items as $it) {
                $stock = WasteStock::where('waste_type_id', $it->waste_type_id)->lockForUpdate()->first();
                if (!$stock || $stock->available_weight < $it->quantity) {
                    DB::rollBack();
                    return back()->with('error', "Stok tidak cukup untuk {$it->type->type_name}");
                }
                $stock->available_weight -= $it->quantity;
                $stock->save();
            }
        }

        if ($newStatus === 'cancelled' && $oldStatus === 'paid') {
            foreach ($tx->items as $it) {
                $stock = WasteStock::firstOrCreate(['waste_type_id' => $it->waste_type_id], ['available_weight'=>0]);
                $stock->available_weight += $it->quantity;
                $stock->save();
            }
        }

        $tx->status = $newStatus;
        $tx->handled_by_staff_id = Auth::id();
        $tx->handled_at = Carbon::now();
        $tx->save();

        DB::commit();
        return redirect()->route('staff.transactions.index')->with('success','Status transaksi diperbarui.');
    } catch (\Throwable $e) {
        DB::rollBack();
        return back()->with('error','Gagal update status: '.$e->getMessage());
    }
}

    public function byWaste(Request $request, $wasteTypeId)
    {
        $type = WasteType::with('category')->findOrFail($wasteTypeId);

        $query = BuyTransaction::with(['user','items.type'])
            ->whereHas('items', function($q) use ($wasteTypeId){
                $q->where('waste_type_id',$wasteTypeId);
            });

        // search filter
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', function($u) use ($q){
                $u->where('name','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            });
        }

        $transactions = $query->orderBy('transaction_date','desc')->get();

        // auto-cancel jika sudah expired
        foreach ($transactions as $tx) {
            if ($tx->status === 'pending' && $tx->expired_at && now()->gt($tx->expired_at)) {
                $tx->status = 'cancelled';
                $tx->save();
            }
        }

        return view('staff.wastes.transactions', compact('type','transactions'));
    }

}
