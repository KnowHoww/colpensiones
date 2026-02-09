<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionAcreditacion extends Model
{
    use HasFactory;
    protected $table = 'investigacion_acreditacion';
    protected $fillable = [
        'id',
        'idInvestigacion',
        'idBeneficiario',
        'acreditacion',
        'conclusion',
        'resumen',
    ];
}
