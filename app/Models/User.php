<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'id'; // default Laravel
    public $incrementing = true;
    protected $keyType = 'int';

=======
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
>>>>>>> 9e2416b (tampilan Landing page header,hero, tentang kami, faq)
    protected $fillable = [
        'name',
        'email',
        'password',
<<<<<<< HEAD
        'phone_number',
        'address',
        'date_of_birth',
        'gender',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


}
=======
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
>>>>>>> 9e2416b (tampilan Landing page header,hero, tentang kami, faq)
