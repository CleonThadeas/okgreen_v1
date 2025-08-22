<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    protected $table = 'waste_categories';
    protected $fillable = ['category_name'];

    public function types()
    {
        return $this->hasMany(WasteType::class, 'waste_category_id');
    }
}
