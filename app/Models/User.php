<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    // Columns that can be mass assigned
    protected $fillable = [
        'sr_code',
        'username',
        'email',
        'password', // PostgreSQL column name must be 'password'
    ];

    // Hide password when serializing
    protected $hidden = [
        'password',
    ];

    // Disable timestamps if you only have created_at manually
    public $timestamps = false;
}
