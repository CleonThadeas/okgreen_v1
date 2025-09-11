<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPoint extends Model
{
    protected $table = 'user_points';
    public $timestamps = true;

    protected $fillable = ['user_id','points'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
