<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionAuxilioFunerario extends Model
{
    use HasFactory;
    protected $table = 'investigacion_auxilio_funerarios';
    protected $fillable = [
        'idInvestigacion',
        'valorGastosFunerarios',
        'tipoPago',
        'personaSufrago',
        'detalleServicio',
        'cesionDerechos',
        'personaCesionDerechos',
        'personaDocumento'
    ];
}
