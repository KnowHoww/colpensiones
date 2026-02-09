<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionLaborCampo extends Model
{
    use HasFactory;
    protected $table = 'investigacion_labor_campos';
    protected $fillable = [
        'idInvestigacion',
        'laborCampo',
    ];
}
