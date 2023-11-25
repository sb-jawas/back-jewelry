<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Componentes extends Model
{
    use HasFactory;

    protected $table = 'componentes';
    public $timestamps = true;
    
    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    public $fillable = [
        'id',
        'name',
        'desc',
    ];
}
