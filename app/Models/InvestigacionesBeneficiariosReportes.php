<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;

class InvestigacionesBeneficiariosReportes extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_beneficiarios';
    protected $fillable = [
        'IdInvestigacion',
        'TipoDocumento',
        'NumeroDocumento',
        'PrimerNombre',
        'SegundoNombre',
        'PrimerApellido',
        'SegundoApellido',
        'Parentesco',
        'iParentesco',
        'Nit',
        'InstitucionEducativa',
        'resumen_acreditacion',
        'resumen_conclusion',
        'acreditacion'
    ];

   
    public function investigacion()
    {
        return $this->belongsTo(InvestigacionesReportes::class, 'idInvestigacion');
    }
}
