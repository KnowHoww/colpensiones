<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionesValidacionDocumentalCausante extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_validacion_documental_causante';
    protected $fillable = [
        'idInvestigacion',
        'cedula',
        'defuncion',
        'observacion',
        'matrimonio',
        'gastos_funebre',
        'gastos_funerarios'
    ];
}
