<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrazabilidadActividadesRealizadas extends Model
{
    use HasFactory;
    protected $table = 'investigacion_trazabilidad_actividades_realizadas';
    protected $fillable = [
        'idInvestigacion',
        'idUsuario',
        'actividad',
        'fecha',
        'observacion'
    ];
    
    
    public function creadores()
    {
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }
}
