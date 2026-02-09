<?php

namespace App\Exports;

use App\Models\CentroCostos;
use App\Models\InvestigacionesReportes;
use App\Models\InvestigacionesBeneficiarios;
use App\Models\InvestigacionesAcreditacionesReportes;
use Maatwebsite\Excel\Concerns\FromQuery;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class InformeInvestigacionesRaw implements FromQuery
{
    public function Query()
    {
        ini_set('memory_limit', '2048M');
       
        $mostrarBeneficiarios = 0;
        if (request()->has('beneficiarios') && request('beneficiarios') != 0) {


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
                'analistaColpensiones.name as name_analistaColpensiones',
                'analistaColpensiones.lastname as lastname_analistaColpensiones',
                'aprobadorColpensiones.name as name_aprobadorColpensiones',
                'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones',
                'departamentos.departamento as departamento',
                'municipios.municipio as municipio',
                'tipo_documento.nombre as iTipoDocumento',
                'tipo_tramite.nombre as iTipoTramite',
                'centro_costos.nombre as iCentroCosto',
                'tipo_prioridad.nombre as iPrioridad',
                'investigacion_region.Nombre as Iregion',
               // '´0´ as acreditados'
            )
          //      ->leftJoin('investigaciones_observaciones_estados', 'investigaciones_observaciones_estados.idInvestigacion', '=', 'investigaciones.id')
                ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
                ->leftJoin('tipo_investigacion', 'investigaciones.TipoInvestigacion', '=', 'tipo_investigacion.codigo')
                ->leftJoin('tipo_riesgo', 'investigaciones.TipoRiesgo', '=', 'tipo_riesgo.codigo')
                ->leftJoin('detalle_riesgo', 'investigaciones.DetalleRiesgo', '=', 'detalle_riesgo.codigo')
                ->leftJoin('tipo_documento', 'investigaciones.TipoDocumento', '=', 'tipo_documento.codigo')
                ->leftJoin('tipo_tramite', 'investigaciones.TipoTramite', '=', 'tipo_tramite.codigo')
                ->leftJoin('centro_costos', 'investigaciones.CentroCosto', '=', 'centro_costos.codigo')        
                ->leftJoin('users as analista', 'analista.id', '=', 'investigacion_asignacion.Analista')
                ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
                ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
                ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
                ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
                ->leftJoin('departamentos', 'departamentos.id', '=', 'investigaciones.departamentoRegion')
                ->leftJoin('investigacion_region', 'investigacion_region.id', '=', 'investigaciones.region')
                ->leftJoin('tipo_prioridad', 'tipo_prioridad.id', '=', 'investigaciones.Prioridad')
                ->leftJoin('municipios', 'municipios.id', '=', 'investigaciones.ciudadRegion')            
                ->distinct('investigaciones.id');

            if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $data->whereBetween('MarcaTemporal', [request('fecha_inicio'), request('fecha_fin')]);
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

            if (request()->has('centroCostoNumero') && request('centroCostoNumero') != 0) {
                $costos = CentroCostos::where('id',request('centroCostoNumero'))->first();
                $data->where('investigaciones.CentroCosto', $costos->codigo);
            }

            if (request()->has('tipo_investigacion') && request('tipo_investigacion') != 0) {
                $data->where('investigaciones.TipoInvestigacion', request('tipo_investigacion'));
            }
            $data = $data->get();
            $mostrarBeneficiarios = 1;
            
            foreach ($data as $dato) {
                $dato->acreditados = 0;
                $beneficiarios = InvestigacionesBeneficiariosReportes::select('investigaciones_beneficiarios.*',
                                    'investigacion_acreditacion.acreditacion as acreditacion',
                                    'parentesco.nombre as iParentesco',  
                                    'investigacion_acreditacion.resumen as resumen_acreditacion', 
                                    'investigacion_acreditacion.conclusion as resumen_conclusion')
                                    ->leftjoin('investigacion_acreditacion', 'investigacion_acreditacion.idBeneficiario', 'investigaciones_beneficiarios.id')
                                    ->leftjoin('parentesco', 'parentesco.id', 'investigaciones_beneficiarios.Parentesco')
                                    ->where('investigaciones_beneficiarios.idInvestigacion', $dato->id)->get();
                $beneficiarioData = [];
                foreach ($beneficiarios as $i => $beneficiario) {

                    if ($beneficiario->acreditacion == 14) {
                        $dato->acreditados = $dato->acreditados . 1;
                    }
                    $beneficiarioData['beneficiario' . ($i . 1)] = $beneficiario;
                }
                $dato->beneficiarios = $beneficiarioData;
            }
       }
       else
       {
            $sql = 'SELECT  distinct investigaciones.*,investigador.name as name_investigador,' .
            'investigador.lastname as lastname_investigador,' . 
            'coordinador.name as name_coordinador,' . 
            'coordinador.lastname as lastname_coordinador,' . 
            'tipo_investigacion.nombre as iTipoInvestigacion,' . 
            'detalle_riesgo.nombre as iDetalleRiesgo,' . 
            'tipo_riesgo.nombre as iTipoRiesgo,' . 
            'states.name as estado,' . 
            'analista.name as name_analista,' . 
            'analista.lastname as lastname_analista,' . 
            'analistaColpensiones.name as name_analistaColpensiones,' . 
            'analistaColpensiones.lastname as lastname_analistaColpensiones,' . 
            'aprobadorColpensiones.name as name_aprobadorColpensiones,' . 
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones,' . 
            'departamentos.departamento as departamento,' . 
            'municipios.municipio as municipio,' . 
            'tipo_documento.nombre as iTipoDocumento,' . 
            'tipo_tramite.nombre as iTipoTramite,' . 
            'centro_costos.nombre as iCentroCosto,' . 
            'tipo_prioridad.nombre as iPrioridad,' . 
            'investigacion_region.Nombre as Iregion,' . 
            ' ifnull(investigacion_acreditacion.acreditaciones ,0)   as acreditados' . 
            ' FROM investigaciones LEFT JOIN ' . 
            ' states on states.id=investigaciones.estado  LEFT JOIN ' . 
            ' investigacion_asignacion on investigacion_asignacion.idInvestigacion=investigaciones.id  LEFT JOIN ' . 
            'tipo_investigacion ON investigaciones.TipoInvestigacion =tipo_investigacion.codigo LEFT JOIN ' . 
            'tipo_riesgo ON investigaciones.TipoRiesgo =tipo_riesgo.codigo LEFT JOIN ' . 
            'detalle_riesgo ON investigaciones.DetalleRiesgo =detalle_riesgo.codigo LEFT JOIN ' . 
            'tipo_documento ON investigaciones.TipoDocumento =tipo_documento.codigo LEFT JOIN ' . 
            'tipo_tramite ON investigaciones.TipoTramite =tipo_tramite.codigo LEFT JOIN ' . 
            'centro_costos ON investigaciones.CentroCosto =centro_costos.codigo LEFT JOIN    ' .      
            'users as analista ON analista.id =investigacion_asignacion.Analista LEFT JOIN ' . 
            'users as coordinador ON coordinador.id =investigacion_asignacion.CoordinadorRegional LEFT JOIN ' . 
            'users as investigador ON investigador.id =investigacion_asignacion.Investigador LEFT JOIN ' . 
            'users as analistaColpensiones ON analistaColpensiones.id =investigaciones.analista LEFT JOIN ' . 
            'users as aprobadorColpensiones ON aprobadorColpensiones.id =investigaciones.aprobador LEFT JOIN ' . 
            'departamentos ON departamentos.id =investigaciones.departamentoRegion LEFT JOIN ' . 
            'investigacion_region ON investigacion_region.id =investigaciones.region LEFT JOIN ' . 
            'tipo_prioridad ON tipo_prioridad.id =investigaciones.Prioridad LEFT JOIN ' . 
            'municipios ON municipios.id =investigaciones.ciudadRegion  LEFT JOIN  ' . 
            '  (SELECT IdInvestigacion, count(*) AS acreditaciones FROM investigacion_acreditacion  GROUP BY IdInvestigacion) investigacion_acreditacion ON investigaciones.id = investigacion_acreditacion.IdInvestigacion  ';
            $wherein = ' where ';
            if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $sql = $sql . $wherein .' MarcaTemporal BETWEEN \'' . request('fecha_inicio') . '\' and \'' .  request('fecha_fin') . '\'';
                $wherein =' and ';
            }

            if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && !(request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $sql = $sql . $wherein .'  MarcaTemporal > \'' . request('fecha_inicio') . '\'  ';
                $wherein =' and ';
            }

            if (!(request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $sql =  $sql . $wherein .'  MarcaTemporal <= \'' . request('fecha_fin') . '\'  ';
                $wherein =' and ';
            }

            if (!(request()->has('fecha_inicio') && request('fecha_inicio') != null) && !(request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                 $sql =  $sql . $wherein .'  MarcaTemporal  BETWEEN CURDATE() - INTERVAL 60 DAY AND CURDATE() ' ;
                $wherein =' and ';
            }

            if (request()->has('estado') && request('estado') != 0) {
                $sql =  $sql . $wherein .' investigaciones.estado  = ' . request('estado');
                $wherein =' and ';
            }

            if (request()->has('centroCostoNumero') && request('centroCostoNumero') != 0) {
                $sql =  $sql . $wherein .' investigaciones.CentroCosto  = ' . request('centroCostoNumero');
                $wherein =' and ';

            }

            if (request()->has('tipo_investigacion') && request('tipo_investigacion') != 0) {
                $sql =  $sql . $wherein .' investigaciones.TipoInvestigacion  = \'' . request('tipo_investigacion') . '\'';
                $wherein =' and ';
            }               


            $data = DB::SELECT ($sql);
       }
      

        return collect($data);
    }
}
