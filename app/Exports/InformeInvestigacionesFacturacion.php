<?php

namespace App\Exports;

use App\Models\CentroCostos;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class InformeInvestigacionesFacturacion implements FromView
{
    public function view(): View
    {
        ini_set('memory_limit', '2048M');
      $sql = 'select investigaciones.id AS idInvestigacion, ' .
				'investigaciones.NumeroRadicacionCaso, ' .
				'investigaciones.TipoDocumento , ' .
				'investigaciones.NumeroDeDocumento , ' .
				'investigaciones.PrimerNombre , ' .
				'investigaciones.PrimerApellido , ' .
				'ucase(CONCAT_WS(\' \',Analista.name ,Analista.lastname)) AS Analista, ' .
				'ucase(CONCAT_WS(\' \',Investigador.name , Investigador.lastname)) AS Investigador, ' .
				'investigaciones.TipoInvestigacion , ' .
				'tipo_investigacion.nombre AS tipoInvestigacion, ' .
				' DATE_FORMAT( investigaciones.FechaFinalizacion, "%Y-%m-%d") as FechaFinalizacion , ' .
				'investigaciones.region, ' .
				'municipios.municipio, ' .
				'departamentos.departamento, ' .
				'investigaciones_facturacion.id AS id, ' .
				'states.name AS estado, ' .
				'if(investigaciones_facturacion.FechaFacturacion is null,0,1 ) AS facturado, ' .
				'investigaciones_facturacion.FechaRadicacion, ' .
				' DATE_FORMAT( investigaciones_facturacion.FechaFacturacion, "%Y-%m-%d") as FechaFacturacion, ' .
				'investigaciones_facturacion.idTarifa, ' .
				'investigaciones_facturacion.radicador, ' .
				'investigaciones_facturacion.facturador, ' .
				'investigaciones_facturacion.FechaCorrecionRadicacion, ' .
				'investigaciones_facturacion.idEstadoRadicacion, ' .
				'investigaciones_facturacion.idEstadoFacturacion, ' .
				'tarifa.tarifa, ' .
				'radicador.name AS radicador, ' .
				'facturador.name AS facturador ' .
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'municipios ON investigaciones.ciudadRegion = municipios.id LEFT JOIN ' .
				'departamentos ON investigaciones.departamentoRegion = departamentos.id LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'tarifa ON (investigaciones.TipoInvestigacion = tarifa.TipoInvestigacion AND  (
				(tarifa.idRegion is null and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida > 0 and ( investigaciones_facturacion.extendida>0 ) )
				) )  LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'users as Analista ON Analista.id = investigacion_asignacion.Analista LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  LEFT JOIN ' .
				'users as radicador ON radicador.id = investigaciones_facturacion.radicador LEFT JOIN ' .
				'users as facturador  ON facturador.id = investigaciones_facturacion.facturador  ' .
				'WHERE not investigaciones.FechaFinalizacion IS null ' ;
				
		if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $sql = $sql . ' and investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
			//$fechaInicio = request()->has('fecha_inicio');
        }		
		if ((request()->has('centroCosto') && request('centroCosto') > 0) ) {
            $sql = $sql . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
			
        }	
		
		$sql = $sql . ' ORDER BY investigaciones.FechaFinalizacion; '; 
		$data = DB::Select($sql);
        return view('exports.InformeInvestigacionesFacturacion', ['data' => $data]);
    }
}
