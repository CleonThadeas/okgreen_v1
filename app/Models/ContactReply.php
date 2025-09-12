<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    use HasFactory;

    protected $primaryKey = 'reply_id';

    protected $fillable = [
        'message_id',
        'admin_id',
        'user_id',
        'sender_type',
        'reply',
    ];

    /**
     * Relasi ke pesan utama
     */
    public function message()
    {
        return $this->belongsTo(ContactMessage::class, 'message_id', 'message_id');
    }

    /**
     * Relasi ke admin (jika pengirim admin)
     */
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id', 'id');
    }

    /**
     * Relasi ke user (jika pengirim user)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
