<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'method_id';
    public $timestamps = true;

    protected $fillable = ['name','description'];
}
