<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;

class InvestigacionesAcreditacionesReportes extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_beneficiarios';
    protected $fillable = [
        'IdInvestigacion',
        'acreditaciones'
    ];

   
    public function investigacion()
    {
        return $this->belongsTo(InvestigacionesReportes::class, 'idInvestigacion');
    }
}
