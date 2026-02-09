<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionEntrevistaFamiliares extends Model
{
    use HasFactory;
    protected $table = 'investigacion_entrevista_familiares';
    protected $fillable = [
        'idInvestigacion',
        'laborCampo',
    ];
}
