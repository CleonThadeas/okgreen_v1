<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BuyTransaction extends Model
{
    protected $table = 'buy_transactions';
    protected $fillable = [
        'user_id','total_amount','status','transaction_date',
        'expired_at','qr_text','handled_by_staff_id','handled_at'
      ];
      

    public function items()
{
    return $this->hasMany(\App\Models\BuyCartItem::class, 'buy_transaction_id');
}


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
