<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionAsignacion extends Model
{
    use HasFactory;
    protected $table = 'Investigacion_asignacion';
    protected $fillable = [
        'idInvestigacion',
        'CoordinadorRegional',
        'Investigador',
        'Auxiliar',
        'Analista'
    ];

    public function CoordinadorRegionales()
    {
        return $this->belongsTo(User::class, 'CoordinadorRegional', 'id');
    }

    public function Investigadores()
    {
        return $this->belongsTo(User::class, 'Investigador', 'id');
    }
}
