<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionesDocumentos extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_documentos';
    protected $fillable = [
        'nombre',
        'codigo_documental'
    ];
}
