<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class WasteType extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'waste_category_id',
        'type_name',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id');
    }
}
