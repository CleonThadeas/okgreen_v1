<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class BuyTransaction extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'buy_transactions';

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'transaction_date',
        'payment_method',
        'shipping_method',
        'receiver_name',
        'address',
        'phone',
        'shipping_cost',
        'expired_at',
        'qr_text',
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Relasi ke item transaksi
    public function items()
    {
        return $this->hasMany(BuyCartItem::class, 'buy_transaction_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Cek apakah transaksi masih aktif
    public function isActive()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        return $this->expired_at
            ? Carbon::now()->lessThanOrEqualTo($this->expired_at)
            : false;
    }
}
