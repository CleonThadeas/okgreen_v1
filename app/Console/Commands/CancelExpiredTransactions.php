<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BuyTransaction;
use Carbon\Carbon;

class CancelExpiredTransactions extends Command
{
    protected $signature = 'transactions:cancel-expired';
    protected $description = 'Cancel pending buy_transactions that have expired';

    public function handle()
    {
        $now = Carbon::now();
        $txs = BuyTransaction::where('status','pending')->whereNotNull('expired_at')->where('expired_at','<', $now)->get();
        $count = 0;
        foreach ($txs as $tx) {
            $tx->status = 'cancelled';
            $tx->handled_at = $now;
            $tx->save();
            $count++;
        }
        $this->info("Cancelled {$count} expired transactions.");
        return 0;
    }
}
