<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestigacionesReportes extends Model
{
    use HasFactory;
    protected $table = 'investigaciones';
    protected $fillable = [
        "id",
        'MarcaTemporal',
        'NumeroRadicacionCaso',
        'IdCase',
        'TipoInvestigacion',
        'TipoRiesgo',
        'DetalleRiesgo',
        'TipoTramite',
        'TipoSolicitud',
        'TipoSolicitante',
        'TipoPension',
        'TipoDocumento',
        'NumeroDeDocumento',
        'PrimerNombre',
        'SegundoNombre',
        'PrimerApellido',
        'SegundoApellido',
        'RadicadoAsociado',
        'Solicitud',
        'investigador',
        'TelefonoCausante',
        'Ciudad',
        'DireccionCausante',
        'analista',
        'aprobador',
        'estado',
        'Prioridad',
        'Junta',
        'NumeroDictamen',
        'FechaDictamen',
        'Observacion',
        'PuntoAtencion',
        'DireccionPunto',
        'Acreditacion',
        'Fecha_finalizacion',
        'FechaLimite',
        'CentroCosto',
        'nombreCarpeta',
        'CasoPadreOriginal',
        'region',
        'FechaFinalizacion',
        'FechaObjecion',
        'FechaFinalizacionObjecion',
        'FechaAprobacion',
        'FechaAprobacionObjecion',
        'FechaCancelacion',
        'cantidadObjeciones',
        'esObjetado',
        'departamentoRegion',
        'ciudadRegion',
        'departamento',
        'municipio',
        'Iregion',
        'iTipoInvestigacion',
        'iDetalleRiesgo',
        'iTipoRiesgo',
        'iTipoDocumento',
        'iTipoTramite',
        'iPrioridad',
        'iCentroCosto',
        'acreditados',
    ];

    public function estados()
    {
        return $this->belongsTo(States::class, 'estado', 'id');
    }

   

    public function beneficiarios()
    {
        return $this->hasMany(InvestigacionesBeneficiariosReportes::class, 'idInvestigacion');
    }

 
    
}
