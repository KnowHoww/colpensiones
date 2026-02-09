<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitudes extends Model
{
    use HasFactory;
    protected $fillable = [
        'NumeroRadicacionCaso',
        'IdCase',
        'TipoInvestigacion',
        'TipoRiesgo',
        'DetalleRiesgo',
        'TipoDocumento',
        'NumeroDeDocumento',
        'PrimerNombre',
        'SegundoNombre',
        'PrimerApellido',
        'SegundoApellido',
        'RadicadoAsociado',
        'Solicitud',
        'Documentos',
    ];
}
