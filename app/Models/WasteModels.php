<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteType extends Model
{
    use HasFactory;

    protected $table = 'waste_types';

    protected $fillable = [
        'category_id',
        'type_name',
        'description',
        'price_per_unit',
        'available_weight',
        'photo',
    ];

    public function category()
    {
        return $this->belongsTo(WasteCategory::class, 'category_id');
    }
}

class WasteCategory extends Model
{
    use HasFactory;

    protected $table = 'waste_categories';

    protected $fillable = [
        'category_name',
        'description',
    ];

    public function wastes()
    {
        return $this->hasMany(WasteType::class, 'category_id');
    }
}
