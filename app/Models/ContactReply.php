<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    protected $table = 'contact_replies';
    protected $primaryKey = 'reply_id';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'message_id','sender_id','sender_role','message'
    ];

    public function contact()
    {
        return $this->belongsTo(ContactMessage::class, 'message_id', 'message_id');
    }
}
