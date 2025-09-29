<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',  
        'receiver_id',
        'receiver_role',
        'title',
        'message',
        'is_read',
        'meta',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'meta'    => 'array',
    ];

    public function scopeForReceiver(Builder $q, string $role, $id)
    {
        return $q->where('receiver_role', $role)
                 ->where('receiver_id', $id);
    }

    public function markRead()
    {
        $this->is_read = true;
        $this->save();
    }
}
