<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolUser extends Model
{
    use HasFactory;
    protected $table = 'rol_has_user';
    public $timestamps = true;
    
    protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];

    protected $fillable = [
        'id',
        'user_id',
        'rol_id',
    ];
}
