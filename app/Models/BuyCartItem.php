<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuyCartItem extends Model
{
    protected $table = 'buy_cart_items';

    protected $fillable = [
        'buy_transaction_id','waste_type_id','quantity',
        'price_per_unit','subtotal'
    ];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(BuyTransaction::class,'buy_transaction_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(WasteType::class,'waste_type_id');
    }
}
