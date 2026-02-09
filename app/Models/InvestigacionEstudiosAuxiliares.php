<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionEstudiosAuxiliares extends Model
{
    use HasFactory;
    protected $table = 'investigacion_estudios_auxiliares';
    protected $fillable = [
        'idInvestigacion',
        'labor',
        'entrevistaExtrajuicio',
        'hallazgos',
        'observacion',
    ];
}
