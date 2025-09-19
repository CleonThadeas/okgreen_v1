<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellTransaction extends Model
{
    use HasFactory;

    protected $table = 'transactions'; // sesuaikan dengan nama tabel di database

    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'payment_method',
        'created_at',
        'updated_at',
    ];
}
