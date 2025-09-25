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
        'expired_at',   // ✅ tambahkan ini
        'qr_text'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
        'expired_at' => 'datetime',   // ✅ otomatis jadi Carbon instance
    ];

    // Relasi ke item transaksi
    public function items()
    {
        return $this->hasMany(BuyCartItem::class, 'buy_transaction_id', 'id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Cek apakah transaksi masih aktif (pakai expired_at kalau ada)
    public function isActive()
    {
        if ($this->expired_at) {
            return $this->status === 'pending' && now()->lessThanOrEqualTo($this->expired_at);
        }

        // fallback kalau expired_at belum ada, pakai transaction_date + 5 menit
        return $this->status === 'pending'
            && now()->lessThanOrEqualTo(Carbon::parse($this->transaction_date)->addMinutes(5));
    }
}

