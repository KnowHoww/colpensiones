<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class generarDocumentacion extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_documentos';

    
    protected $fillable = [
        'idInvestigacion',
        'NombreNemotecnia',
        'NombreOriginal',
        'CodigoDocumental',
        'peso',
        'folios',
        'observacion',
        'created_at'
    ];

}
