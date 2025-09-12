<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteStock extends Model
{
    use HasFactory;

    // pakai nama tabel yang sesuai di DB
    protected $table = 'waste_stock';  

    protected $fillable = ['waste_type_id','available_weight'];

    public function type()
    {
        return $this->belongsTo(WasteType::class, 'waste_type_id');
    }
}
