<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionEntrevistaSolicitante extends Model
{
    use HasFactory;
    protected $table = 'investigacion_entrevista_solicitante';
    protected $fillable = [
        'idInvestigacion',
        'trabajo_campo',
    ];
}
