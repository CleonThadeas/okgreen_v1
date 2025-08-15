<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'id'; // ganti jika di DB kamu kolomnya admin_id
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'address'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
