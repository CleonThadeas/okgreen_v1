<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ContactMessage extends Model
{
    use HasFactory;

    protected $primaryKey = 'message_id'; 

    protected $fillable = [
        'user_id',
        'subject',
        'message',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(ContactReply::class, 'message_id', 'message_id');
    }
}
