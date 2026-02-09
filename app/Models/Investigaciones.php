<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investigaciones extends Model
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
        'ciudadRegion'
    ];

    public function estados()
    {
        return $this->belongsTo(States::class, 'estado', 'id');
    }
    public function Acreditaciones()
    {
        return $this->belongsTo(States::class, 'Acreditacion', 'id');
    }
    public function TipoInvestigaciones()
    {
        return $this->belongsTo(TipoInvestigacion::class, 'TipoInvestigacion', 'codigo');
    }
    public function TipoRiesgos()
    {
        return $this->belongsTo(TipoRiesgo::class, 'TipoRiesgo', 'codigo');
    }
    public function DetalleRiesgos()
    {
        return $this->belongsTo(DetalleRiesgo::class, 'DetalleRiesgo', 'codigo');
    }
    public function TipoTramites()
    {
        return $this->belongsTo(TipoTramite::class, 'TipoTramite', 'codigo');
    }
    public function TipoSolicitudes()
    {
        return $this->belongsTo(TipoSolicitud::class, 'TipoSolicitud', 'codigo');
    }
    public function TipoSolicitantes()
    {
        return $this->belongsTo(TipoSolicitante::class, 'TipoSolicitante', 'codigo');
    }
    public function TipoPensiones()
    {
        return $this->belongsTo(TipoPension::class, 'TipoPension', 'codigo');
    }
    public function TipoDocumentos()
    {
        return $this->belongsTo(TipoDocumento::class, 'TipoDocumento', 'codigo');
    }
    public function Prioridades()
    {
        return $this->belongsTo(TipoPrioridad::class, 'Prioridad', 'id');
    }
    public function CentroCostos()
    {
        return $this->belongsTo(CentroCostos::class, 'CentroCosto', 'codigo');
    }

    public function beneficiarios()
    {
        return $this->hasMany(InvestigacionesBeneficiarios::class, 'idInvestigacion');
    }

    public function Regiones()
    {
        return $this->belongsTo(InvestigacionRegion::class, 'region');
    }
    
    public function juntas()
    {
        return $this->belongsTo(Juntas::class, 'Junta');
    }
    
    public function departamentos()
    {
        return $this->belongsTo(Departamento::class, 'departamentoRegion');
    }
    
    public function municipios()
    {
        return $this->belongsTo(Municipio::class, 'ciudadRegion');
    }
}
