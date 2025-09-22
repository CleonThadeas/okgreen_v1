<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PointRedemption extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'point_redemptions';

    protected $fillable = [
        'user_id',
        'reward_id',
        'redeemed_at',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reward()
    {
        return $this->belongsTo(PointReward::class, 'reward_id');
    }
}
