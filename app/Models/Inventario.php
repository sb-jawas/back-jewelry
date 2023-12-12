<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventario';
    public $timestamps = true;
    
    protected $hidden = [
        'updated_at',
        'created_at',
        'id'
    ];

    protected $fillable = [
        'cantidad',
        'componente_id'
    ];
}
