<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;
    protected $table = 'lote';
    public $timestamps = true;
    
    protected $hidden = [
        'updated_at'
    ];

    protected $fillable = [
        'lat',
        'long',
        'observation',
        'user_id',
        'created_at',
        'status_code_id',
    ];

    function statusLote(){
        return $this->hasMany(StatusCode::class, 'id','status_code_id');
    }

}
