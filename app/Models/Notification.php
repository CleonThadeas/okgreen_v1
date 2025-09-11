<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = true;

    protected $fillable = ['user_id','message','status']; // status: unread|read

    public const STATUS_UNREAD = 'unread';
    public const STATUS_READ   = 'read';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
