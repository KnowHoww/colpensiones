<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionGastosVivienda extends Model
{
    use HasFactory;
    protected $table = 'investigacion_gastos_vivienda';
    protected $fillable = [
        'idInvestigacion',
        'serviciosPublicosValor',
        'serviciosPublicosValorAporte',
        'arriendoValor',
        'arriendoValorAporte',
        'mercadoValor',
        'mercadoValorAporte',
        'otrosValor',
        'otrosValorAporte',
        'totalValor',
        'totalValorAporte',
        'observacion'
    ];
}
