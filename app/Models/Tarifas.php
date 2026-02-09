<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarifas extends Model
{
    use HasFactory;
    protected $table = 'tarifa';
    protected $fillable = [
        'id',
        'nombre',
        'idRegion',
        'tarifa',
        'TipoInvestigacion',
        'extendida'
    ];
}
