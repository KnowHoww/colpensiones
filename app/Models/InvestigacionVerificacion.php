<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionVerificacion extends Model
{
    use HasFactory;
    protected $table = 'investigacion_verificacion';
    protected $fillable = [
        'idInvestigacion',
        'idBeneficiario',
        'ciudad',
        'telefono',
        'direccion',
    ];
}
