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
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'ubi',
        'observation',
        'user_id',
        'status_code_id',
    ];

    	
}
