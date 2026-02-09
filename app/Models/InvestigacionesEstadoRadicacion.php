<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class investigacionesEstadoRadicacion extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_estados_radicacion';
    protected $fillable = [
        'id',
        'idInvestigacion',
        'idUsuario',
        'idEstadoRadicacion',
        'observacion',
        'create_at'
    ];

    
	public function creador()
    {
        return $this->belongsTo(User::class, 'idUsuario', 'id');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoRadicacion::class, 'idEstadoRadicacion', 'id');
    }
    
}
