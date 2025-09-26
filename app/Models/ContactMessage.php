<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $table = 'contact_messages';
    protected $primaryKey = 'message_id';
    public $incrementing = true; // true kalau INT auto increment
    protected $keyType = 'int';  // ganti 'string' kalau UUID
    public $timestamps = true;

    protected $fillable = [
        'user_id','subject','message','status'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    public function replies()
    {
        return $this->hasMany(ContactReply::class, 'message_id', 'message_id')->orderBy('created_at','asc');
    }
}
