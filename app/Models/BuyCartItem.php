<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BuyCartItem extends Model
{
    protected $table = 'buy_cart_items';
    protected $fillable = ['buy_transaction_id','waste_type_id','quantity','price_per_unit','subtotal'];

    public function type()
{
    return $this->belongsTo(\App\Models\WasteType::class, 'waste_type_id');
}


    public function transaction()
    {
        return $this->belongsTo(BuyTransaction::class, 'buy_transaction_id');
    }
}
