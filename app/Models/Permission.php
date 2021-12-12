<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = "permission";
    
    protected $fillable = [
        'granted_by',
        'granted_to',
        'address'
    ];

    public $timestamps = false;
}
