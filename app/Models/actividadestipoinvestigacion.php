<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class actividadestipoinvestigacion extends Model
{
    use HasFactory;
    protected $table = 'actividades_tipo_investigacion';
    protected $fillable = [
        'ActividadName',
        'TipoInvestigacionCode',
        'TipoInvestigacionID'
    ];
}
