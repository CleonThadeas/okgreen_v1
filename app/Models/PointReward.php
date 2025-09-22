<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PointReward extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'point_rewards';

    protected $fillable = [
        'reward_name',
        'required_points',
        'stock',
    ];

    public function redemptions()
    {
        return $this->hasMany(PointRedemption::class, 'reward_id');
    }
}
