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
        'transaction_date'
    ];

    // Relasi ke item transaksi
    public function items()
    {
        return $this->hasMany(BuyCartItem::class, 'buy_transaction_id', 'id');
    }

    // Relasi ke user (supaya bisa akses data user yang buat transaksi)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Cek apakah transaksi masih aktif (pending dan belum lewat 5 menit)
    public function isActive()
    {
        return $this->status === 'pending'
            && Carbon::now()->lessThanOrEqualTo(
                Carbon::parse($this->transaction_date)->addMinutes(5)
            );
    }
}
