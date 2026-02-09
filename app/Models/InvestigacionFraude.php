<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionFraude extends Model
{
    use HasFactory;
    protected $table = 'investigacion_fraude';
    protected $fillable = [
        'idInvestigacion',
        'fraude',
        'observacion',
    ];

    public function fraudes()
    {
        return $this->belongsTo(States::class, 'fraude', 'id');
    }
    
    


}
