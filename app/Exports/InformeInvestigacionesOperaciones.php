<?php

namespace App\Exports;

use App\Models\CentroCostos;
use App\Models\InvestigacionesReportes;
use App\Models\InvestigacionesBeneficiarios;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;


class InformeInvestigacionesOperaciones implements FromView
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
            'analistaColpensiones.name as name_analistaColpensiones',
            'analistaColpensiones.lastname as lastname_analistaColpensiones',
            'aprobadorColpensiones.name as name_aprobadorColpensiones',
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones',
            'departamentos.departamento as departamento',
            'municipios.municipio as municipio',
            'tipo_documento.nombre as iTipoDocumento',
            'tipo_tramite.nombre as iTipoTramite',
            'centro_costos.nombre as iCentroCosto',
            'investigacion_region.Nombre as Iregion',
            'investigacion_fraude.fraude as idFraude',
            'states_fraude.name as estadoFraude',
            DB::raw('(SELECT created_at FROM investigaciones_observaciones_estados 
                      WHERE investigaciones_observaciones_estados.idInvestigacion = investigaciones.id 
                      AND investigaciones_observaciones_estados.idEstado = 6 
                      ORDER BY created_at DESC LIMIT 1) as fechaRevision') 
        )
      //      ->leftJoin('investigaciones_observaciones_estados', 'investigaciones_observaciones_estados.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('investigacion_fraude', 'investigacion_fraude.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('states as states_fraude', 'states_fraude.id', '=', 'investigacion_fraude.fraude') // Relación para obtener 'Sí' o 'No'
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
            ->leftJoin('municipios', 'municipios.id', '=', 'investigaciones.ciudadRegion')            
            ->distinct('investigaciones.id');
			
          if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $data->whereBetween('MarcaTemporal', [request('fecha_inicio'), request('fecha_fin')]);
        }

        if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && !(request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $data->whereDate('MarcaTemporal', '>=' , request('fecha_inicio'));
        }

        if (!(request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $data->whereDate('MarcaTemporal', '<=' , request('fecha_inicio'));
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

        $mostrarBeneficiarios = 0;
        if (request()->has('beneficiarios') && request('beneficiarios') != 0) {
            $mostrarBeneficiarios = 1;
            foreach ($data as $dato) {
                $beneficiarios = InvestigacionesBeneficiarios::select('investigaciones_beneficiarios.*', 'investigacion_acreditacion.acreditacion as acreditacion', 'investigacion_acreditacion.resumen as resumen_acreditacion', 'investigacion_acreditacion.conclusion as resumen_conclusion')->leftjoin('investigacion_acreditacion', 'investigacion_acreditacion.idBeneficiario', 'investigaciones_beneficiarios.id')->where('investigaciones_beneficiarios.idInvestigacion', $dato->id)->get();
                $beneficiarioData = [];
                foreach ($beneficiarios as $i => $beneficiario) {
                    $beneficiarioData['beneficiario' . ($i + 1)] = $beneficiario;
                }
                $dato->beneficiarios = $beneficiarioData;
            }
        }

        return view('exports.InformeInvestigacionesOperaciones', ['data' => $data, 'mostrarBeneficiarios' => $mostrarBeneficiarios, 'solo_fecha' => $solo_fecha ]);
    }
}
