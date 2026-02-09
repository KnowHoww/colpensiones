<?php

namespace App\Exports;

use App\Models\InvestigacionesReportes;
use App\Models\InvestigacionesBeneficiariosReportes;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InformeMisInvestigaciones implements FromView
{
    public function view(): View
    {
        ini_set('memory_limit', '2048M');
        $data = InvestigacionesReportes::select(
            'investigaciones.*',
            'investigador.name as name_investigador',
            'investigador.lastname as lastname_investigador',
            'coordinador.name as name_coordinador',
            'coordinador.lastname as lastname_coordinador',
            'tipo_investigacion.nombre as iTipoInvestigacion',
            'detalle_riesgo.nombre as iDetalleRiesgo',
            'tipo_riesgo.nombre as iTipoRiesgo',
            'analista.name as name_analista',
            'analista.lastname as lastname_analista',
            'auxiliar.name as name_auxiliar',
            'auxiliar.lastname as lastname_auxiliar',
            'analistaColpensiones.name as name_analistaColpensiones',
            'analistaColpensiones.lastname as lastname_analistaColpensiones',
            'aprobadorColpensiones.name as name_aprobadorColpensiones',
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones',
            'departamentos.departamento as departamento',
            'municipios.municipio as municipio',
            'states.name as iEstado' ,
            'tipo_documento.nombre as iTipoDocumento',
            'tipo_tramite.nombre as iTipoTramite',
            'centro_costos.nombre as iCentroCosto',
            'tipo_prioridad.nombre as iPrioridad',
            'investigacion_region.Nombre as Iregion',
        )
        ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
        ->leftJoin('tipo_investigacion', 'investigaciones.TipoInvestigacion', '=', 'tipo_investigacion.codigo')
        ->leftJoin('tipo_riesgo', 'investigaciones.TipoRiesgo', '=', 'tipo_riesgo.codigo')
        ->leftJoin('detalle_riesgo', 'investigaciones.DetalleRiesgo', '=', 'detalle_riesgo.codigo')
        ->leftJoin('tipo_documento', 'investigaciones.TipoDocumento', '=', 'tipo_documento.codigo')
        ->leftJoin('tipo_tramite', 'investigaciones.TipoTramite', '=', 'tipo_tramite.codigo')
        ->leftJoin('centro_costos', 'investigaciones.CentroCosto', '=', 'centro_costos.codigo')        
        ->leftJoin('states', 'investigaciones.estado', '=', 'states.id')
        ->leftJoin('users as analista', 'analista.id', '=', 'investigacion_asignacion.Analista')
        ->leftJoin('users as auxiliar', 'auxiliar.id', '=', 'investigacion_asignacion.Auxiliar')
        ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
        ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
        ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
        ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
        ->leftJoin('departamentos', 'departamentos.id', '=', 'investigaciones.departamentoRegion')
        ->leftJoin('investigacion_region', 'investigacion_region.id', '=', 'investigaciones.region')
        ->leftJoin('tipo_prioridad', 'tipo_prioridad.id', '=', 'investigaciones.Prioridad')
        ->leftJoin('municipios', 'municipios.id', '=', 'investigaciones.ciudadRegion')   ;         
        
       if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $data->whereBetween('MarcaTemporal', [request('fecha_inicio'), Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->addDays(1)->format('Y-m-d')]);
        }

        if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && !(request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $data->whereDate('MarcaTemporal', '>=' , request('fecha_inicio'));
        }

        if (!(request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $data->whereDate('MarcaTemporal', '<=' , request('fecha_fin'));
        }

        if (!(request()->has('fecha_inicio') && request('fecha_inicio') != null) && !(request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $data->whereDate('MarcaTemporal', '>=' , now()->subDays(60)->endOfDay());
        }

        if (request()->has('estado') && request('estado') != 0) {
            $data->where('investigaciones.estado', request('estado'));
        }

        if (request()->has('tipo_investigacion') && request('tipo_investigacion') != 0) {
            $data->where('investigaciones.TipoInvestigacion', request('tipo_investigacion'));
        }

        $data = $data->where('investigaciones.analista',Auth::user()->id)->groupBy('investigaciones.id')->get();

        return view('exports.InformeInvestigacionesCompleto', ['data' => $data, 'mostrarBeneficiarios' => 0]);
    }
}
