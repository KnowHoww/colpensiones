<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoInvestigacion extends Model
{
    use HasFactory;
    protected $table = 'tipo_investigacion';
    protected $fillable = [
        'nombre',
        'codigo',
        'TiempoEntrega'
    ];
}
