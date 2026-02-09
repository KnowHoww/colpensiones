<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionValidacionDocumentalBeneficiarios extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_validacion_documental_beneficiarios';
    protected $fillable = [
        'idInvestigacion',
        'idBeneficiario',
        'cedula',
        'nacimiento',
        'incapacidad',
        'escolaridad',
    ];
}
