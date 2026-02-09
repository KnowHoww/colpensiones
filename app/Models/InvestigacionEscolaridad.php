<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionEscolaridad extends Model
{
    use HasFactory;
    protected $table = 'investigacion_escolaridad';
    protected $fillable = [
        'idInvestigacion',
        'idBeneficiario',
        'institucion',
        'correo',
        'telefono',
        'observacion'
    ];
}
