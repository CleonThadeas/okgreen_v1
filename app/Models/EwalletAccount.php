<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EwalletAccount extends Model
{
    protected $table = 'ewallet_accounts';
    public $timestamps = true;

    protected $fillable = ['user_id','balance'];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactions()
    {
        return $this->hasMany(EwalletTransaction::class, 'ewallet_account_id');
    }
}
