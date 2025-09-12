<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteCategory extends Model
{
    use HasFactory;

    protected $table = 'waste_categories';
    protected $fillable = ['category_name', 'description'];

    public function wastes()
    {
        return $this->hasMany(WasteType::class, 'waste_category_id');
    }
}
