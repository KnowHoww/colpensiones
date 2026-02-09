<?php

namespace App\Exports;

use App\Models\InvestigacionesReportes;
use App\Models\InvestigacionesBeneficiariosReportes;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class InformeInvestigacionesCompleto implements FromView
{
    protected $estado;

    public function __construct($estado)
    {
        return $this->estado = $estado;
    }

    public function view(): View
    {
        ini_set('memory_limit', '2048M');
        $data = InvestigacionesReportes::select(
            'investigaciones.*',
            'investigador.name as name_investigador',
            'investigador.lastname as lastname_investigador',
            'coordinador.name as name_coordinador',
            'coordinador.lastname as lastname_coordinador',
            'analista.name as name_analista',
            'analista.lastname as lastname_analista',
            'analistaColpensiones.name as name_analistaColpensiones',
            'analistaColpensiones.lastname as lastname_analistaColpensiones',
            'aprobadorColpensiones.name as name_aprobadorColpensiones',
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones',
            'tipo_investigacion.nombre as iTipoInvestigacion',
            'detalle_riesgo.nombre as iDetalleRiesgo',
            'tipo_riesgo.nombre as iTipoRiesgo',
            'departamentos.departamento as departamento',
            'municipios.municipio as municipio',
            'tipo_documento.nombre as iTipoDocumento',
            'tipo_tramite.nombre as iTipoTramite',
            'centro_costos.nombre as iCentroCosto',
            'tipo_prioridad.nombre as iPrioridad',
            'investigacion_region.Nombre as Iregion' 
           
        )
            ->leftJoin('investigaciones_observaciones_estados', function($join)
            {
                $join->on(function($join2) {
                    $join2->on('investigaciones_observaciones_estados.id','=',7);
                    $join2->orOn('investigaciones_observaciones_estados.id','=',16);
                });
                $join->on('investigaciones_observaciones_estados.idInvestigacion', '=', 'investigaciones.id');
            }) //'investigaciones_observaciones_estados.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('tipo_investigacion', 'investigaciones.TipoInvestigacion', '=', 'tipo_investigacion.codigo')
            ->leftJoin('tipo_riesgo', 'investigaciones.TipoRiesgo', '=', 'tipo_riesgo.codigo')
            ->leftJoin('detalle_riesgo', 'investigaciones.DetalleRiesgo', '=', 'detalle_riesgo.codigo')
            ->leftJoin('tipo_documento', 'investigaciones.TipoDocumento', '=', 'tipo_documento.codigo')
            ->leftJoin('tipo_tramite', 'investigaciones.TipoTramite', '=', 'tipo_tramite.codigo')
            ->leftJoin('centro_costos', 'investigaciones.CentroCosto', '=', 'centro_costos.codigo')
            ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
            ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
            ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
            ->leftJoin('departamentos', 'departamentos.id', '=', 'investigaciones.departamentoRegion')
            ->leftJoin('tipo_prioridad', 'tipo_prioridad.id', '=', 'investigaciones.Prioridad')
            ->leftJoin('municipios', 'municipios.id', '=', 'investigaciones.ciudadRegion')
            ->leftJoin('investigacion_region', 'investigacion_region.id', '=', 'investigaciones.region');


        if ($this->estado != null) {
            $data = $data->where('investigaciones.estado', $this->estado);
        }
        $data = $data->get();

        foreach ($data as $dato) {
            $dato->acredita = 0;
            $beneficiarios = InvestigacionesBeneficiariosReportes::select('investigaciones_beneficiarios.*', 
                            'investigacion_acreditacion.acreditacion',
                            'parentesco.nombre as iParentesco', 
                            'investigacion_acreditacion.resumen as resumen_acreditacion', 
                            'investigacion_acreditacion.conclusion as resumen_conclusion')
                            ->leftjoin('investigacion_acreditacion', 'investigacion_acreditacion.idBeneficiario', 'investigaciones_beneficiarios.id')
                            ->leftjoin('parentesco', 'parentesco.id', 'investigaciones_beneficiarios.Parentesco')
                            ->where('investigaciones_beneficiarios.idInvestigacion', $dato->id)->get();
            $beneficiarioData = [];
            foreach ($beneficiarios as $i => $beneficiario) {
                if ($beneficiario->acreditacion == 14) {
                    $dato->acreditados = $dato->acreditados + 1;
                }
                $beneficiarioData['beneficiario' . ($i + 1)] = $beneficiario;
            }
            $dato->beneficiarios = $beneficiarioData;
        }
        return view('exports.InformeInvestigacionesCompleto', ['data' => $data]);
    }
}
