<?php

namespace App\Http\Controllers\Api\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BuyTransaction;
use App\Models\WasteStock;
use App\Models\WasteType;

class StaffTransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:staff'); // guard khusus staff
    }

    /**
     * GET /api/staff/transactions
     */
    public function index()
    {
        $txs = BuyTransaction::with('user')
            ->orderBy('created_at','desc')
            ->paginate(20);

        return response()->json($txs);
    }

    /**
     * GET /api/staff/transactions/{id}
     */
    public function show($id)
    {
        $tx = BuyTransaction::with('items.type.category','user')->find($id);

        if (!$tx) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        return response()->json($tx);
    }

    /**
     * PUT /api/staff/transactions/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,paid,cancelled']);

        $tx = BuyTransaction::with('items.type')->findOrFail($id);

        DB::beginTransaction();
        try {
            $oldStatus = $tx->status;
            $newStatus = $request->status;

            if ($newStatus === 'paid' && $oldStatus !== 'paid') {
                foreach ($tx->items as $it) {
                    $stock = WasteStock::where('waste_type_id', $it->waste_type_id)->lockForUpdate()->first();
                    if (!$stock || $stock->available_weight < $it->quantity) {
                        DB::rollBack();
                        return response()->json([
                            'message' => "Stok tidak cukup untuk {$it->type->type_name}"
                        ], 400);
                    }
                    $stock->available_weight -= $it->quantity;
                    $stock->save();
                }
            }

            if ($newStatus === 'cancelled' && $oldStatus === 'paid') {
                foreach ($tx->items as $it) {
                    $stock = WasteStock::firstOrCreate(
                        ['waste_type_id' => $it->waste_type_id],
                        ['available_weight' => 0]
                    );
                    $stock->available_weight += $it->quantity;
                    $stock->save();
                }
            }

            $tx->status = $newStatus;
            $tx->handled_by_staff_id = Auth::id();
            $tx->handled_at = Carbon::now();
            $tx->save();

            DB::commit();

            return response()->json([
                'message' => 'Status transaksi diperbarui',
                'data'    => $tx
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal update status',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/staff/transactions/by-waste/{wasteTypeId}
     */
    public function byWaste(Request $request, $wasteTypeId)
    {
        $type = WasteType::with('category')->find($wasteTypeId);

        if (!$type) {
            return response()->json(['message' => 'Waste type tidak ditemukan'], 404);
        }

        $query = BuyTransaction::with(['user','items.type'])
            ->whereHas('items', function($q) use ($wasteTypeId) {
                $q->where('waste_type_id',$wasteTypeId);
            });

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', function($u) use ($q){
                $u->where('name','like',"%$q%")
                  ->orWhere('email','like',"%$q%");
            });
        }

        $transactions = $query->orderBy('transaction_date','desc')->get();

        foreach ($transactions as $tx) {
            if ($tx->status === 'pending' && $tx->expired_at && now()->gt($tx->expired_at)) {
                $tx->status = 'cancelled';
                $tx->save();
            }
        }

        return response()->json([
            'waste_type'   => $type,
            'transactions' => $transactions
        ]);
    }
}
