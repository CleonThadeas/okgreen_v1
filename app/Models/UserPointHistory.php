<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPointHistory extends Model
{
    use HasFactory;

    protected $table = 'user_point_histories';

    protected $fillable = [
        'user_id',
        'source',
        'reference_id',
        'points_change',
        'description',
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
