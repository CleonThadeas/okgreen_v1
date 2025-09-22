<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Nama tabel sesuai dengan migration
    protected $table = 'addresses';

    // Kolom yang bisa diisi mass assignment
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
