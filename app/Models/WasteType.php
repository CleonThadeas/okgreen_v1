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
    
    protected $table = 'waste_types';
    protected $fillable = ['waste_category_id','type_name','description','price_per_unit','photo'];

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id');
    }


        public function stock()
    {
        return $this->hasOne(WasteStock::class, 'waste_type_id');
    }

    public function sellRequests()
    {
        return $this->hasMany(\App\Models\SellWaste::class, 'waste_type_id');
    }


}

