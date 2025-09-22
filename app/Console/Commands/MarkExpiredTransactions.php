<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\BuyTransaction;
use App\Models\BuyCartItem;
use App\Models\WasteStock;

class MarkExpiredTransactions extends Command
{
    protected $signature = 'transactions:mark-expired';
    protected $description = 'Mark pending transactions expired -> canceling and restock';

    public function handle()
    {
        $now = Carbon::now();
        $txs = BuyTransaction::where('status','pending')
            ->whereNotNull('expired_at')
            ->where('expired_at','<',$now)
            ->get();

        $count = 0;
        foreach ($txs as $tx) {
            DB::beginTransaction();
            try {
                // restock items
                $items = BuyCartItem::where('buy_transaction_id', $tx->id)->get();
                foreach ($items as $it) {
                    $stock = WasteStock::where('waste_type_id', $it->waste_type_id)->lockForUpdate()->first();
                    if ($stock) {
                        $stock->available_weight += $it->quantity;
                        $stock->save();
                    }
                }

                $tx->status = 'canceling';
                $tx->handled_at = $now;
                $tx->save();

                DB::commit();
                $count++;
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error('Failed to process tx '.$tx->id.': '.$e->getMessage());
            }
        }

        $this->info("Marked {$count} transactions as canceling.");
    }
}
