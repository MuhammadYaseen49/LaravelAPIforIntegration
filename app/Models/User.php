<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "users";
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'age',
        'profile_picture',
        'Verification_Token',
        'email_verified_at',
        'PasswordReset_Token'
    ];

    public $timestamps = false;
   
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
