<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class investigacionesObservacionesEstado extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_observaciones_estados';
    protected $fillable = [
        'idInvestigacion',
        'idUsuario',
        'idEstado',
        'observacion',
        'CausalPrimariaObjecion',
        'CausalSecundariaObjecion'
    ];

    public function estados()
    {
        return $this->belongsTo(States::class, 'idEstado', 'id');
    }

    public function creadores()
    {
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

    public function CausalPrimaria()
    {
        return $this->belongsTo(TipoObjecion::class, 'CausalPrimariaObjecion', 'id');
    }
    
    public function CausalSecundaria()
    {
        return $this->belongsTo(TipoObjecion::class, 'CausalSecundariaObjecion', 'id');
    }
}
