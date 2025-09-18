<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellWasteType extends Model
{
    protected $table = 'sell_waste_types';
    protected $fillable = ['waste_category_id','type_name','points_per_kg'];

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id');
    }
}
