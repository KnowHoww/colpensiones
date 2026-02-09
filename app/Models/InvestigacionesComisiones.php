<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionesComisiones extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_comision';
    protected $fillable = [
        'id',
        'idInvestigacion',
        'FechaComision',
        'investigador',
        'auxiliar',
        'tarifaInvestigador',
        'tarifaAuxiliar',
        'AuxiliarCompleta',
        'porBeneficiario',
        'doble',
        'idInformeInvestigador',
        'idInformeAuxiliar',
        'ValorInvestigador',
        'ValorAuxiliar',        
    ];

    

    public function investigador()
    {
        return $this->belongsTo(User::class, 'investigador', 'id');
    }
    public function auxiliar()
    {
        return $this->belongsTo(User::class, 'auxiliar', 'id');
    }

 
 	
}
