<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteDespiece extends Model
{
    use HasFactory;
    protected $table = 'componentes_has_lote';
    public $timestamps = true;
    
    protected $hidden = [
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
        'cantidad',
        'clasificador_id',
        'lote_id',
        'componente_id'
    ];
}
