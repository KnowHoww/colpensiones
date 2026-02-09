<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRiesgo extends Model
{
    use HasFactory;
    protected $table = 'detalle_riesgo';
    protected $fillable = [
        'nombre',
    ];
}
