<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joya extends Model
{
    use HasFactory;
    public $timestamps = true;
    
    protected $hidden = [
        'updated_at'
    ];

    protected $fillable = [
        'id',
        'user_id',
        'desc',
        'foto'
    ];
}
