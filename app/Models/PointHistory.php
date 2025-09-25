<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointHistory extends Model
{
    protected $table = 'point_history'; // pastikan nama tabel sesuai DB
    protected $fillable = [
        'user_id','source','reference_id','points_change','description'
    ];
    public $timestamps = true; // sesuaikan jika tabel tidak punya created_at
}
