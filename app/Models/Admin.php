<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $guard = 'admin'; // untuk Admin.php

    protected $primaryKey = 'admin_id';
    public $incrementing = true;
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
