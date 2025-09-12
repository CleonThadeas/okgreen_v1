<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    use HasFactory;

    protected $table = 'waste_types';
    protected $fillable = [
        'waste_category_id',
        'type_name',
        'description',
        'price_per_unit',
        'photo'
    ];

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'waste_category_id');
    }

    public function stock()
    {
        return $this->hasOne(WasteStock::class, 'waste_type_id');
    }
}
