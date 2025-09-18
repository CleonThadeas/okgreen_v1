<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class WasteCategory extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'waste_categories'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'category_name',
    ];
}
