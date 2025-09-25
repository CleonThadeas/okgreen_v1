<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyTransaction extends Model
{
    protected $table = 'buy_transactions';

    protected $fillable = [
        'user_id','total_amount','status','transaction_date',
        'payment_method','shipping_method','receiver_name','address','phone','shipping_cost',
        'qr_text','expired_at','handled_by_staff_id','handled_at'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'expired_at'       => 'datetime',
        'handled_at'       => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(BuyCartItem::class, 'buy_transaction_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
