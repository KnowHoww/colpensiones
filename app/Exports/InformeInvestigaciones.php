<?php

namespace App\Exports;

use App\Models\CentroCostos;
use App\Models\InvestigacionesReportes;
use App\Models\InvestigacionesBeneficiariosReportes;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InformeInvestigaciones implements FromView
{
    public function view(): View
    {
        ini_set('memory_limit', '2048M');
       

/*


        */
        

        $mostrarBeneficiarios = 0;
        if (request()->has('beneficiarios') && request('beneficiarios') != 0) {
            $data = InvestigacionesReportes::select(
                'investigaciones.*',
                'investigador.name as name_investigador',
                'investigador.lastname as lastname_investigador',
                'coordinador.name as name_coordinador',
                'coordinador.lastname as lastname_coordinador',
                'states.name as iEstado' , 
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
                'tipo_documento.nombre as iTipoDocumento',
                'tipo_tramite.nombre as iTipoTramite',
                'centro_costos.nombre as iCentroCosto',
                'tipo_prioridad.nombre as iPrioridad',
                'investigacion_region.Nombre as Iregion',
                'investigacion_fraude.fraude as idFraude',
                'states_fraude.name as estadoFraude',
                DB::raw('(SELECT created_at FROM investigaciones_observaciones_estados 
                      WHERE investigaciones_observaciones_estados.idInvestigacion = investigaciones.id 
                      AND investigaciones_observaciones_estados.idEstado = 6 
                      ORDER BY created_at DESC LIMIT 1) as fechaRevision') 
               // '´0´ as acreditados'
            )
          //      ->leftJoin('investigaciones_observaciones_estados', 'investigaciones_observaciones_estados.idInvestigacion', '=', 'investigaciones.id')
                ->leftJoin('investigacion_fraude', 'investigacion_fraude.idInvestigacion', '=', 'investigaciones.id')
                ->leftJoin('states as states_fraude', 'states_fraude.id', '=', 'investigacion_fraude.fraude') // Relación para obtener 'Sí' o 'No'
                ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
                ->leftJoin('tipo_investigacion', 'investigaciones.TipoInvestigacion', '=', 'tipo_investigacion.codigo')
                ->leftJoin('states', 'investigaciones.estado', '=', 'states.id')
                ->leftJoin('tipo_riesgo', 'investigaciones.TipoRiesgo', '=', 'tipo_riesgo.codigo')
                ->leftJoin('detalle_riesgo', 'investigaciones.DetalleRiesgo', '=', 'detalle_riesgo.codigo')
                ->leftJoin('tipo_documento', 'investigaciones.TipoDocumento', '=', 'tipo_documento.codigo')
                ->leftJoin('tipo_tramite', 'investigaciones.TipoTramite', '=', 'tipo_tramite.codigo')
                ->leftJoin('centro_costos', 'investigaciones.CentroCosto', '=', 'centro_costos.codigo')        
                ->leftJoin('users as analista', 'analista.id', '=', 'investigacion_asignacion.Analista')
                ->leftJoin('users as auxiliar', 'auxiliar.id', '=', 'investigacion_asignacion.Auxiliar')
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
                $data->whereBetween('MarcaTemporal', [request('fecha_inicio'), Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->addDays(1)->format('Y-m-d')]);
            }

            if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && !(request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $data->whereDate('MarcaTemporal', '>=' , request('fecha_inicio'));
            }

            if (!(request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $data->whereDate('MarcaTemporal', '<=' , Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->addDays(1)->format('Y-m-d'));
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
            $solo_fecha = 0;
            if (request()->has('solo_fecha') && request('solo_fecha') != 0) {
                $solo_fecha = 1;
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
                        $dato->acreditados ++;
                    }
                    $beneficiarioData['beneficiario' . ($i . 1)] = $beneficiario;
                }
                $dato->beneficiarios = $beneficiarioData;
            }
       }
       else
       {
            $sql = 'SELECT  distinct investigaciones.*, investigador.name as name_investigador,' .
            'investigador.lastname as lastname_investigador,' . 
            'coordinador.name as name_coordinador,' . 
            'coordinador.lastname as lastname_coordinador,' . 
            'tipo_investigacion.nombre as iTipoInvestigacion,' . 
            'detalle_riesgo.nombre as iDetalleRiesgo,' . 
            'tipo_riesgo.nombre as iTipoRiesgo,' . 
            'states.name as iEstado,' . 
            'analista.name as name_analista,' . 
            'analista.lastname as lastname_analista,' . 
            'auxiliar.name as name_auxiliar,'.
            'auxiliar.lastname as lastname_auxiliar,' .           
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
            ' investigacion_fraude.fraude as idFraude, '.
            'states_fraude.name as estadoFraude, '.
            ' ifnull(investigacion_acreditacion.acreditaciones ,0)   as acreditados,' . 
            
            '(SELECT ioe.created_at FROM investigaciones_observaciones_estados ioe WHERE ioe.idInvestigacion = investigaciones.id AND ioe.idEstado = 6 ORDER BY ioe.created_at DESC LIMIT 1) as fechaRevision' . // Subconsulta corregida


            ' FROM investigaciones LEFT JOIN ' . 
            'investigacion_fraude on investigacion_fraude.idInvestigacion = investigaciones.id LEFT JOIN '.
            'states as states_fraude on  states_fraude.id = investigacion_fraude.fraude LEFT JOIN '.
            ' states on states.id=investigaciones.estado  LEFT JOIN ' . 
            ' investigacion_asignacion on investigacion_asignacion.idInvestigacion=investigaciones.id  LEFT JOIN ' . 
            'tipo_investigacion ON investigaciones.TipoInvestigacion =tipo_investigacion.codigo LEFT JOIN ' . 
            'tipo_riesgo ON investigaciones.TipoRiesgo =tipo_riesgo.codigo LEFT JOIN ' . 
            'detalle_riesgo ON investigaciones.DetalleRiesgo =detalle_riesgo.codigo LEFT JOIN ' . 
            'tipo_documento ON investigaciones.TipoDocumento =tipo_documento.codigo LEFT JOIN ' . 
            'tipo_tramite ON investigaciones.TipoTramite =tipo_tramite.codigo LEFT JOIN ' . 
            'centro_costos ON investigaciones.CentroCosto =centro_costos.codigo LEFT JOIN    ' .      
            'users as analista ON analista.id =investigacion_asignacion.Analista LEFT JOIN ' . 
            'users as auxiliar ON auxiliar.id =investigacion_asignacion.Auxiliar LEFT JOIN ' . 
            'users as coordinador ON coordinador.id =investigacion_asignacion.CoordinadorRegional LEFT JOIN ' . 
            'users as investigador ON investigador.id =investigacion_asignacion.Investigador LEFT JOIN ' . 
            'users as analistaColpensiones ON analistaColpensiones.id =investigaciones.analista LEFT JOIN ' . 
            'users as aprobadorColpensiones ON aprobadorColpensiones.id =investigaciones.aprobador LEFT JOIN ' . 
            'departamentos ON departamentos.id =investigaciones.departamentoRegion LEFT JOIN ' . 
            'investigacion_region ON investigacion_region.id =investigaciones.region LEFT JOIN ' . 
            'tipo_prioridad ON tipo_prioridad.id =investigaciones.Prioridad LEFT JOIN ' . 
            'municipios ON municipios.id =investigaciones.ciudadRegion  LEFT JOIN  ' . 
            '  (SELECT IdInvestigacion, count(*) AS acreditaciones FROM investigacion_acreditacion where acreditacion = 14 GROUP BY IdInvestigacion) investigacion_acreditacion ON investigaciones.id = investigacion_acreditacion.IdInvestigacion  ';
            $wherein = ' where ';
            if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $sql = $sql . $wherein .' MarcaTemporal BETWEEN \'' . request('fecha_inicio') . '\' and \'' .  Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->addDays(1)->format('Y-m-d') . '\'';
                $wherein =' and ';
            }

            if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && !(request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $sql = $sql . $wherein .'  MarcaTemporal > \'' . request('fecha_inicio') . '\'  ';
                $wherein =' and ';
            }

            if (!(request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
                $sql =  $sql . $wherein .'  MarcaTemporal <= \'' . Carbon::createFromFormat('Y-m-d', request('fecha_fin'))->addDays(1)->format('Y-m-d') . '\'  ';
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
                $sql =  $sql . $wherein .' centro_costos.id  = ' . request('centroCostoNumero');
                $wherein =' and ';

            }
            $solo_fecha = 0;
            if (request()->has('solo_fecha') && request('solo_fecha') != 0) {
                $solo_fecha = 1;
            }

            if (request()->has('tipo_investigacion') && request('tipo_investigacion') != 0) {
                $sql =  $sql . $wherein .' investigaciones.TipoInvestigacion  = \'' . request('tipo_investigacion') . '\'';
                $wherein =' and ';
            }               


            $data = DB::SELECT ($sql);
       }
      

        return view('exports.InformeInvestigacionesCompleto', ['data' => $data, 'mostrarBeneficiarios' => $mostrarBeneficiarios, 'solo_fecha' => $solo_fecha ]);
    }
}
