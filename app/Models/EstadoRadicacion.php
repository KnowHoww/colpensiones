<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoRadicacion extends Model
{
    use HasFactory;
    protected $table = 'estados_radicacion';
    protected $fillable = [
        'id',
        'name',
        'create_at'
    ];
}
