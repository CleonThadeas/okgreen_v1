<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    protected $table = 'user_activities';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'activity_type', // enum('sell','buy','redeem','watch_edu','login','feedback')
        'reference_id',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
