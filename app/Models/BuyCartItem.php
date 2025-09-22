<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class BuyCartItem extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'buy_cart_items';

    protected $fillable = [
        'buy_transaction_id', 'waste_type_id', 'quantity', 'price_per_unit', 'subtotal'
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
