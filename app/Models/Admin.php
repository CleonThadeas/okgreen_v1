<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'admins';
    protected $primaryKey = 'id'; 
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'email', 
        'password', 
        'phone_number', 
        'address'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
