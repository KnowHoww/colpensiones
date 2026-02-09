<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Request as HttpRequest;

class InvestigacionesBeneficiarios extends Model
{
    use HasFactory;
    protected $table = 'investigaciones_beneficiarios';
    protected $fillable = [
        'IdInvestigacion',
        'TipoDocumento',
        'NumeroDocumento',
        'PrimerNombre',
        'SegundoNombre',
        'PrimerApellido',
        'SegundoApellido',
        'Parentesco',
        'Nit',
        'InstitucionEducativa',
    ];

    public function Parentescos()
    {
        return $this->belongsTo(Parentesco::class, 'Parentesco', 'codigo');
    }

    public function investigacion()
    {
        return $this->belongsTo(Investigaciones::class, 'idInvestigacion');
    }
}
