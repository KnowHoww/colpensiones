<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionConsultasAntecedentesCausante extends Model
{
    use HasFactory;
    protected $table = 'investigacion_consultas_antecedentes_causante';
    protected $fillable = [
        'idInvestigacion',
        'adres',
        'ruaf',
        'rues',
        'rnec',
        'cufe',
        'sispro',
        'rama_judicial',
        'samai',
        'observacion_adres',
        'observacion_ruaf',
        'observacion_rues',
        'observacion_rnec',
        'observacion_cufe',
        'observacion_sispro',
        'observacion_rama_judicial',
        'observacion_samai'
    ];
}
