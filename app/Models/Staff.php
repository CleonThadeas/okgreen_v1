<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use Notifiable;

    protected $table = 'staff';
    protected $primaryKey = 'id'; // ganti jika di DB kamu kolomnya staff_id
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'address'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
