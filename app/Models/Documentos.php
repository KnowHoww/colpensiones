<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documentos extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_documentos';
    protected $fillable = [
        'idInvestigacion',
        'name',
        'url'
    ];
}
