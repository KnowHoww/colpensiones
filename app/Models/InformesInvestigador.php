<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformesInvestigador extends Model
{
    use HasFactory;
    protected $table = 'informes_investigador';
    protected $fillable = [
        'id',
        'idInvestigador',
        'Inicio',
        'Fin',
        'aceptado',
        'pagado',
        'valor',
    ];

    

 
 	
}
