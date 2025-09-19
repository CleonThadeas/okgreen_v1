<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_date',
        'payment_method',
        'shipping_method',
        'receiver_name',
        'phone',
        'address',
        'shipping_cost',
        'total_amount',
        'status',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke item transaksi
    public function items()
{
    return $this->hasMany(BuyCartItem::class, 'transaction_id');
}

}
