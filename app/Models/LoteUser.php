<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteUser extends Model
{
    use HasFactory;
    protected $table = 'lote_has_user';
    
    public $timestamps = true;
    
    protected $hidden = [
        'updated_at',
        'created_at',
        'id'
    ];

    protected $fillable = [
        'user_id',
        'lote_id'
    ];
}
