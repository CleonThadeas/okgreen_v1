<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EwalletTransaction extends Model
{
    protected $table = 'ewallet_transactions';
    public $timestamps = true;

    protected $fillable = [
        'ewallet_account_id',
        'type',             // enum: credit|debit
        'amount',
        'description',
        'transaction_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    public const TYPE_CREDIT = 'credit';
    public const TYPE_DEBIT  = 'debit';

    public function account()
    {
        return $this->belongsTo(EwalletAccount::class, 'ewallet_account_id');
    }
}
