<?php

namespace App\Exports;

use App\Models\CentroCostos;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class InformeInvestigacionesComision implements FromView
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
				'ucase(CONCAT_WS(\' \',Auxiliar.name ,Auxiliar.lastname)) AS Auxiliar, ' .
				'ucase(CONCAT_WS(\' \',Investigador.name , Investigador.lastname)) AS Investigador, ' .
				'investigaciones.TipoInvestigacion , ' .
				'tipo_investigacion.nombre AS tipoInvestigacion, ' .
				' DATE_FORMAT( investigaciones.FechaFinalizacion, "%Y-%m-%d") as FechaFinalizacion , ' .
				'investigaciones.region, ' .
				'investigaciones_facturacion.id AS id, ' .
				'states.name AS estado, ' .
				'investigaciones_comision.AuxiliarCompleta, ' .
				'investigaciones_comision.porBeneficiario, ' .
				'if(investigaciones_facturacion.FechaFacturacion is null,0,1 ) AS facturado, ' .
				'investigaciones_facturacion.FechaRadicacion, ' .
				' DATE_FORMAT( investigaciones_facturacion.FechaFacturacion, "%Y-%m-%d") as FechaFacturacion, ' .
				'investigaciones_facturacion.idTarifa, ' .
				'investigaciones_comision.FechaComision, ' .
				'investigaciones_facturacion.idEstadoFacturacion, ' .
				'(case when investigacion_asignacion.Investigador is null or investigacion_asignacion.Investigador=0 then 0 when investigaciones_comision.doble>0 then 100000  else comision_investigador.tarifa end )*(case when investigaciones_comision.porBeneficiario>0 then numeroBeneficiarios  else 1 end ) as comision_investigador, ' .
				'(case when investigacion_asignacion.Auxiliar is null or investigacion_asignacion.Auxiliar=0 then 0 else comision_auxiliar.tarifa end ) as comision_auxiliar ' .
				'FROM investigaciones LEFT JOIN ' .
				'(select count(*) as numeroBeneficiarios , idInvestigacion from investigaciones_beneficiarios group by idInvestigacion) beneficiarios ON investigaciones.id = beneficiarios.idInvestigacion LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'investigaciones_comision ON investigaciones.id = investigaciones_comision.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'tarifa_comision as comision_investigador ON (comision_investigador.tipo = \'IN\' AND not investigacion_asignacion.Investigador is null)  LEFT JOIN ' .
				'tarifa_comision as comision_auxiliar ON (((comision_auxiliar.tipo = \'AU\'  and (investigaciones_comision.AuxiliarCompleta = 0 or investigaciones_comision.AuxiliarCompleta is null ))  or (comision_auxiliar.tipo = \'IN\'  and investigaciones_comision.AuxiliarCompleta > 0) ) AND not investigacion_asignacion.Auxiliar is null)  LEFT JOIN ' .
				'users as Auxiliar ON Auxiliar.id = investigacion_asignacion.Auxiliar LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  ' .
				'WHERE  (investigacion_asignacion.Investigador >0 or investigacion_asignacion.Auxiliar>0) ' ;
		
		if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $sql = $sql . ' and Investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
        }		

	
		if ((request()->has('filtro') && request('filtro') != null) ) {
            $sql = $sql . ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' )'  ;
        }	

		if ((request()->has('centroCosto') && request('centroCosto') > 0) ) {
            $sql = $sql . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
        }	


		$sql = $sql . ' ORDER BY investigaciones.FechaFinalizacion ; '; 
		$data = DB::Select($sql);
        return view('exports.InformeInvestigacionesComision', ['data' => $data]);
    }
}
