<?php

namespace App\Http\Controllers;

use App\Mail\NotificarInforme;
use App\Mail\NotificarInformeAprobado;
use Illuminate\Support\Facades\Mail;

use App\Models\Investigaciones;
use App\Models\InvestigacionesFacturacion;
use App\Models\InvestigacionesComisiones;
use App\Models\InformesInvestigador;
use App\Models\InvestigacionAsignacion;
use App\Models\Tarifas;
use App\Models\TarifasComision;
use App\Models\EstadoFacturacion;
use App\Models\CentroCostos;
use App\Http\Controllers\PDFController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;

class InvestigacionesFacturacionController extends Controller
{
    
    public function index(){
        $servicios = Servicios::all();
        $centroCosto = CentroCostos::where('id','!=',1)->get();
		session(['success' => null]); 
		session(['infoError' => null]); 
        return view('home',compact('servicios','centroCosto'));
    }
	
	public function filtros(){
		$title = 'Investigaciones pendientes de facturación';
        $centroCosto = CentroCostos::where('id','!=',1)->get();
		session(['success' => null]); 
		session(['infoError' => null]); 
        return view('investigaciones.facturacion',compact('title', 'centroCosto'));
    }

	
	public function filtrosComision(){
		$title = 'Investigaciones pendientes de comisión';
        $centroCosto = CentroCostos::where('id','!=',1)->get();
		session(['success' => null]); 
		session(['infoError' => null]); 		
        return view('investigaciones.comision',compact('title', 'centroCosto'));
    }
		

    public function buscarInvestigacionesFacturacion(Request $request)
    {
        $title = 'Investigaciones pendientes de facturación';

		$centroCosto = CentroCostos::where('id','!=',1)->get();

        

        $q = $request->input('filtro');
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
				'investigaciones_facturacion.id AS id, ' .
				'states.name AS estado, ' .
				'if(investigaciones_facturacion.FechaFacturacion is null,0,1 ) AS facturado, ' .
				'investigaciones_facturacion.FechaRadicacion, ' .
				' DATE_FORMAT( investigaciones_facturacion.FechaFacturacion, "%Y-%m-%d") as FechaFacturacion, ' .
				'investigaciones_facturacion.idTarifa, ' .
				'investigaciones_facturacion.radicador, ' .
				'investigaciones_facturacion.facturador, ' .
				'investigaciones_facturacion.extendida, ' .
				'investigaciones_facturacion.FechaCorrecionRadicacion, ' .
				'investigaciones_facturacion.idEstadoRadicacion, ' .
				'investigaciones_facturacion.idEstadoFacturacion, ' .
				'tarifa.tarifa, ' .
				'radicador.name AS radicador, ' .
				'facturador.name AS facturador ' .
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'tarifa ON investigaciones.TipoInvestigacion = tarifa.TipoInvestigacion and (
				(tarifa.idRegion is null and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida > 0 and ( investigaciones_facturacion.extendida>0 ) )
				) LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'users as Analista ON Analista.id = investigacion_asignacion.Analista LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  LEFT JOIN ' .
				'users as radicador ON radicador.id = investigaciones_facturacion.radicador LEFT JOIN ' .
				'users as facturador  ON facturador.id = investigaciones_facturacion.facturador  ' .
				'WHERE not investigaciones.FechaFinalizacion IS null ' ;
				
		$sql2 = 'select tipo_investigacion.nombre AS tipoInvestigacion, tarifa.idRegion as idregion,tarifa.tarifa as tarifa, count(*) as numInvestigaciones,  SUM(tarifa.tarifa) AS total ' .
				', sum(if(investigaciones_facturacion.FechaFacturacion is null,0,1 ))as facturadas,  sum(if(investigaciones_facturacion.FechaFacturacion is null,0,tarifa.tarifa )) as facturado '.
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'tarifa ON investigaciones.TipoInvestigacion = tarifa.TipoInvestigacion and (
				(tarifa.idRegion is null and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida > 0 and ( investigaciones_facturacion.extendida>0 ) )
				) LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'users as Analista ON Analista.id = investigacion_asignacion.Analista LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  LEFT JOIN ' .
				'users as radicador ON radicador.id = investigaciones_facturacion.radicador LEFT JOIN ' .
				'users as facturador  ON facturador.id = investigaciones_facturacion.facturador  ' .
				'WHERE not investigaciones.FechaFinalizacion IS null ' ;

		$sql4 = 'select count(*) as numInvestigaciones,  SUM(tarifa.tarifa) AS total ' .
				', sum(if(investigaciones_facturacion.FechaFacturacion is null,0,1 ))as facturadas,  sum(if(investigaciones_facturacion.FechaFacturacion is null,0,tarifa.tarifa )) as facturado '.
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'tarifa ON investigaciones.TipoInvestigacion = tarifa.TipoInvestigacion and (
				(tarifa.idRegion is null and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida = 0 and (investigaciones_facturacion.extendida is null or investigaciones_facturacion.extendida=0 ) )
				or (tarifa.idRegion = investigaciones.region and tarifa.extendida > 0 and ( investigaciones_facturacion.extendida>0 ) )
				)  LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'users as Analista ON Analista.id = investigacion_asignacion.Analista LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  LEFT JOIN ' .
				'users as radicador ON radicador.id = investigaciones_facturacion.radicador LEFT JOIN ' .
				'users as facturador  ON facturador.id = investigaciones_facturacion.facturador  ' .
				'WHERE not investigaciones.FechaFinalizacion IS null ' ;				
		$sql5 = 'select count(distinct idInvestigacion) as objeciones ' .
				'FROM investigaciones_observaciones_estados LEFT JOIN ' .
				'investigaciones ON investigaciones.id = investigaciones_observaciones_estados.idInvestigacion WHERE idEstado = 8 ' ;				
		$sql6 = 'select COUNT(*) as ans FROM investigaciones WHERE not investigaciones.FechaFinalizacion IS NULL AND investigaciones.FechaFinalizacion > investigaciones.FechaLimite ' ;				
	
		if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $sql = $sql . ' and investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
            $sql2 = $sql2 . ' and investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
            $sql4 = $sql4 . ' and investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
            $sql5 = $sql5 . ' and investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
            $sql6 = $sql6 . ' and investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
			//$fechaInicio = request()->has('fecha_inicio');
        }		
		
		
		
		if ((request()->has('centroCosto') && request('centroCosto') > 0) ) {
            $sql = $sql . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
            $sql2 = $sql2 . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
            $sql4 = $sql4 . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
            $sql5 = $sql5 . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
            $sql6 = $sql6 . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
			
        }	

		if ((request()->has('filtro') && request('filtro') != null) ) {
 			$sql = $sql . ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' )  '  ;
 			$sql2 = $sql2 . ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' )  '  ;
 			$sql4 = $sql4 . ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' )  '  ;
 			$sql5 = $sql5 . ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' )  '  ;
 			$sql6 = $sql6 . ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' )  '  ;
        }	


		$sql3 =$sql;	
		$sql3 = $sql3 . ' and not investigaciones_facturacion.id is null ORDER BY investigaciones.FechaFinalizacion; '; 

		$sql = $sql . ' ORDER BY facturado, investigaciones.FechaFinalizacion; '; 
		$sql2 = $sql2 . ' group by tipo_investigacion.nombre,tarifa.idRegion,tarifa.tarifa  HAVING not SUM(tarifa.tarifa) is NULL order by tipo_investigacion.nombre,tarifa.idRegion  '; 
		//$sql4 = $sql4 . ' group by tipo_investigacion.nombre,tarifa.idRegion,tarifa.tarifa  HAVING not SUM(tarifa.tarifa) is NULL  '; 
		$fecha_inicio = request('fecha_inicio');
		$fecha_fin =  request('fecha_fin');
		$centroCostos = request('centroCosto');
		$estadosFacturacion = EstadoFacturacion::all();
		$investigaciones = DB::Select($sql);
		$investigaciones2 = DB::Select($sql3);
		$totales = DB::Select($sql2);
		$filtro =  request('filtro');
		$investigaciones3 = DB::Select($sql4);
		$perfil = Auth::user()->roles[0]->id;	
		$cantidad = count($investigaciones);
		$verDetalle =  request('detail');
		$cantidadPendientes = count($investigaciones2);
		$cantidadFacturados = 0;
		$valorFacturados = 0;
		foreach ($investigaciones3 as $lineaTotal){
			$cantidadFacturados = $cantidadFacturados + $lineaTotal->facturadas;
			$valorFacturados = $valorFacturados + $lineaTotal->facturado;
		}
			//$cantidadFacturados = count($investigaciones2);
		$totalreporte =0;
		foreach ($totales as $lineaTotal)
			$totalreporte = $totalreporte + $lineaTotal->total;
		$error = 	request('error');
		$investigaciones4 = DB::Select($sql5);
		$investigaciones5 = DB::Select($sql6);
		
		$ANSObjeciones = $investigaciones4[0]->objeciones;
		$ANSFueraDeTiempo = $investigaciones5[0]->ans;
		if ($cantidad>0) {
			$ANSObjecionesP = $ANSObjeciones  / $cantidad;
			$ANSFueraDeTiempoP = $ANSFueraDeTiempo  / $cantidad;
			}
		else
		{
			$ANSObjecionesP = 0;
			$ANSFueraDeTiempoP = 0;
		}
		$ANSObjecionesV = 0;
		$ANSFueraDeTiempoV = 0;
		
		if ($ANSObjecionesP<0.005){
			$ANSObjecionesV = 0;
		}
		elseif ($ANSObjecionesP<0.01){
			$ANSObjecionesV = 0.001;
		}
		elseif ($ANSObjecionesP<0.02){
			$ANSObjecionesV = 0.003;
		}
		elseif ($ANSObjecionesP<0.03){
			$ANSObjecionesV = 0.005;
		}
		elseif ($ANSObjecionesP<0.04){
			$ANSObjecionesV = 0.007;
		}
		else {
			$ANSObjecionesV = 0.01;
		}
		
		if ($ANSFueraDeTiempoP<0.01){
			$ANSFueraDeTiempoV = 0;
		}
		elseif ($ANSFueraDeTiempoP<0.02){
			$ANSFueraDeTiempoV = 0.0025;
		}
		elseif ($ANSFueraDeTiempoP<0.03){
			$ANSFueraDeTiempoV = 0.005;
		}
		elseif ($ANSFueraDeTiempoP<0.04){
			$ANSFueraDeTiempoV = 0.0075;
		}
		elseif ($ANSFueraDeTiempoP<0.05){
			$ANSFueraDeTiempoV = 0.01;
		}
		else {
			$ANSFueraDeTiempoV = 0.02;
		}		
        return view('investigaciones.listadoFacturacion', compact('investigaciones', 'title', 'cantidad', 'centroCosto', 'totales','ANSFueraDeTiempoV', 'ANSFueraDeTiempoP', 'ANSFueraDeTiempo', 'ANSObjecionesP', 'ANSObjeciones', 'ANSObjecionesV',  'totalreporte', 'fecha_inicio','estadosFacturacion', 'fecha_fin','centroCostos','perfil','cantidadFacturados','valorFacturados', 'cantidadPendientes','error','verDetalle','filtro'));
    }



    public function descargarXLSFacturacion(Request $request)
    {
		$fecha_actual = now()->format('Y-m-d');
        $nombre_archivo = 'informeFacturacion_' . $fecha_actual . '.xlsx';
        return Excel::download(new \App\Exports\InformeInvestigacionesFacturacion($request), $nombre_archivo);
	}
	public function actualizarFacturados(Request $request)
    {
		$actualizarOK = true;
		$errores ="";
		$estadoFacturacion = request('estadoFacturacion');

		if ((request()->has('fecha_facturacion') && request('fecha_facturacion') != null) || (request()->has('estadoFacturacion') && request('estadoFacturacion') != null ) ){
			
			$investigaciones = request('investigaciones');
			$fechaActualizacion= request('fecha_facturacion');
			
			foreach ($investigaciones as $investigacion)
			{
				$investiga = Investigaciones::find($investigacion);
				$tarifa = Tarifas::where('TipoInvestigacion', $investiga->TipoInvestigacion)->where('idRegion', $investiga->region)->first();
				$tarifac = Tarifas::where('TipoInvestigacion', $investiga->TipoInvestigacion)->where('idRegion', $investiga->region)->count();
				if ($tarifac == 0){
					$tarifa = Tarifas::where('TipoInvestigacion', $investiga->TipoInvestigacion)->whereNull('idRegion')->first();
				}
				
				if ( $tarifa !=null){
					if ((($tarifa->region == $investiga->region) && ($tarifa->TipoInvestigacion == $investiga->TipoInvestigacion) && ($tarifa->extendida == 0)||($tarifa->region == null) && ($tarifa->TipoInvestigacion == $investiga->TipoInvestigacion))){
						$InvestigacionFacturacion = InvestigacionesFacturacion::where('idInvestigacion', $investigacion)->first();
						if ($InvestigacionFacturacion==null){
							$InvestigacionFacturacion = InvestigacionesFacturacion::create ([
								'idInvestigacion' => ($investigacion),
								'idTarifa'=>$tarifa->id,
								'FechaFacturacion' => $fechaActualizacion  ,
								'facturador' =>  Auth::user()->id,
								'idEstadoFacturacion' =>  $estadoFacturacion 
							]);
							DB::commit();
						}
						else {
							$InvestigacionFacturacion::find($InvestigacionFacturacion->id);
							$InvestigacionFacturacion->FechaFacturacion =   $fechaActualizacion;
							$InvestigacionFacturacion->facturador = Auth::user()->id ;
							$InvestigacionFacturacion->idEstadoFacturacion = $estadoFacturacion  ;
							$InvestigacionFacturacion->idTarifa =  $tarifa->id;
							$InvestigacionFacturacion->update(['FechaFacturacion' => $fechaActualizacion ,'facturador' => Auth::user()->id ,'idEstadoFacturacion' => $estadoFacturacion ,'idTarifa' => $tarifa->id ]);
							DB::commit(); 
							if( !$InvestigacionFacturacion->save()){
								$actualizarOK=false;
								
							}
						}
					}
					//$errores = $errores . $investigacion .'.' ; 
				}
				else {
					$actualizarOK=false;
					$errores = $errores . ' Tarifa No encontrada - ' ; 
				}
				//
			}
			
		}
		else {
			$actualizarOK=false;
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Información actualizada correctamente.' . $errores);
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.');
		}
		
	}
	




	public function actualizarTarifaExtendida(string $id, int $establece)
    {
		$actualizarOK = true;
		$errores ="";
		
		if ($id !='' && $establece >=0 ){
			$comisionCount = InvestigacionesFacturacion::where('idInvestigacion', $id)->count();
			if ($comisionCount==0 ){
				$investigacionesFacturacion = InvestigacionesFacturacion::create ([
					'idInvestigacion' => $id,
					'extendida' => $establece
					
				]);
			}
			else{
				$investigacionesFacturacion = InvestigacionesFacturacion::where('idInvestigacion', $id)->first();

				$investigacionesFacturacion::find($investigacionesFacturacion->id);
		
				$investigacionesFacturacion->extendida =  $establece;
				$investigacionesFacturacion->update(['extendida' => $establece ]);
				if( !$investigacionesFacturacion->save()){
					$actualizarOK=false;
				}				
				
			}
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Información actualizada correctamente.' );
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.' . $errores);
		}
		
	}
	
	
	
	
    public function buscarInvestigacionesComision(Request $request)
    {
        $title = 'Investigaciones pendientes de Comisión';

		$centroCosto = CentroCostos::where('id','!=',1)->get();

        $q = $request->input('filtro');
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
				'states.name AS estado, ' .
				'investigaciones_comision.doble, ' .
				'investigaciones_comision.AuxiliarCompleta, ' .
				'investigaciones_comision.porBeneficiario, ' .
				'investigaciones_comision.FechaComision, ' .
				'(case when investigacion_asignacion.Investigador is null or investigacion_asignacion.Investigador=0 then 0 when investigaciones_comision.doble>0 then 100000 else comision_investigador.tarifa end ) as comision_investigador, ' .
				'(case when investigacion_asignacion.Auxiliar is null or investigacion_asignacion.Auxiliar=0 then 0 else comision_auxiliar.tarifa end ) as comision_auxiliar ' .
				'FROM investigaciones LEFT JOIN ' .
				'(select count(*) as numeroBeneficiarios , idInvestigacion from investigaciones_beneficiarios group by idInvestigacion) beneficiarios ON investigaciones.id = beneficiarios.idInvestigacion LEFT JOIN ' .
				'investigaciones_comision ON investigaciones.id = investigaciones_comision.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'tarifa_comision as comision_investigador ON (comision_investigador.tipo = \'IN\' AND not investigacion_asignacion.Investigador is null)  LEFT JOIN ' .
				'tarifa_comision as comision_auxiliar ON (((comision_auxiliar.tipo = \'AU\'  and (investigaciones_comision.AuxiliarCompleta = 0 or investigaciones_comision.AuxiliarCompleta is null ))  or (comision_auxiliar.tipo = \'IN\'  and investigaciones_comision.AuxiliarCompleta > 0) ) AND not investigacion_asignacion.Auxiliar is null)  LEFT JOIN ' .
				'users as Auxiliar ON Auxiliar.id = investigacion_asignacion.Auxiliar LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  ' .
				'WHERE  (investigacion_asignacion.Investigador >0 or investigacion_asignacion.Auxiliar>0) ' ;


       $sql2 = 'SELECT * FROM ( select ucase(CONCAT_WS(\' \',Investigador.name , Investigador.lastname)) AS Investigador, max(idInformeInvestigador) as idInforme,  max(aceptado) as aceptado, ' .
				'if(investigaciones_comision.FechaComision is null,0,1 ) AS comisionado,   investigacion_asignacion.Investigador as id,' .
				'count(*) AS Investigaciones, ' .
				'sum((case when investigacion_asignacion.Investigador is null or investigacion_asignacion.Investigador=0 then 0 when investigaciones_comision.doble>0 then 100000 else tarifa_investigador.tarifa end )*(case when investigaciones_comision.porBeneficiario>0 then numeroBeneficiarios  when investigaciones_comision.doble>0 then 2  else 1 end )) as comision_investigador ' .
				'FROM investigaciones LEFT JOIN investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'(select count(*) as numeroBeneficiarios , idInvestigacion from investigaciones_beneficiarios group by idInvestigacion) beneficiarios ON investigaciones.id = beneficiarios.idInvestigacion LEFT JOIN ' .
				'investigaciones_comision ON investigaciones.id = investigaciones_comision.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'informes_investigador ON informes_investigador.id = investigaciones_comision.idInformeInvestigador LEFT JOIN ' .
				'tarifa_comision as tarifa_investigador ON (tarifa_investigador.tipo = \'IN\' AND not investigacion_asignacion.Investigador is null)  LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  ' .
				'WHERE  investigaciones_comision.FechaComision IS NULL AND ( investigacion_asignacion.Investigador >0 ) %WHERE% ' .
				' GROUP BY ucase(CONCAT_WS(\' \',Investigador.name , Investigador.lastname)) ) A LEFT JOIN' .
				'(select ucase(CONCAT_WS(\' \',Auxiliar.name ,Auxiliar.lastname)) AS Auxiliar, ' .
				'if(investigaciones_comision.FechaComision is null,0,1 ) AS comisionado, ' .
				'count(*) AS Apoyos, ' .
				'sum(case when investigacion_asignacion.Auxiliar is null or investigacion_asignacion.Auxiliar=0 then 0 else tarifa_auxiliar.tarifa end ) as comision_auxiliar ' .
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'investigaciones_comision ON investigaciones.id = investigaciones_comision.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'tarifa_comision as tarifa_auxiliar ON (((tarifa_auxiliar.tipo = \'AU\'  and (investigaciones_comision.AuxiliarCompleta = 0 or investigaciones_comision.AuxiliarCompleta is null )) or (tarifa_auxiliar.tipo = \'IN\'  and investigaciones_comision.AuxiliarCompleta > 0) )  AND not investigacion_asignacion.Auxiliar is null)  LEFT JOIN ' .
				'users as Auxiliar ON Auxiliar.id = investigacion_asignacion.Auxiliar ' .
				'WHERE  investigaciones_comision.FechaComision IS NULL AND (investigacion_asignacion.Auxiliar>0)  %WHERE% ' .
 				'GROUP BY ucase(CONCAT_WS(\' \',Auxiliar.name ,Auxiliar.lastname)) ) b ON a.Investigador =b.Auxiliar '  ;
				

				
		if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $sql = $sql . ' and Investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
			$sql2 = str_replace('%WHERE%', ' and Investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY) %WHERE% ', $sql2)  ;
        }		

	
		if ((request()->has('filtro') && request('filtro') != null) ) {
            $sql = $sql . ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' )'  ;
			$sql2 = str_replace('%WHERE%', ' and (investigaciones.id = \'' . request('filtro') . '\' or investigaciones.NumeroRadicacionCaso like \'%' . request('filtro') . '%\' ) %WHERE% ', $sql2)  ;
        }	

		if ((request()->has('centroCosto') && request('centroCosto') > 0) ) {
            $sql = $sql . ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  '  ;
			$sql2 = str_replace('%WHERE%', ' and investigaciones.CentroCosto  =  \'' . request('centroCosto') . '\'  ', $sql2)  ;
           
			
        }	
		$sql2 = str_replace('%WHERE%', ' ', $sql2)  ;
	

		$sql = $sql . ' ORDER BY investigaciones.FechaFinalizacion; '; 
		$sql2 = $sql2 . ' ORDER BY Investigador; '; 
		
		$fecha_inicio = request('fecha_inicio');
		$fecha_fin =  request('fecha_fin');
		$verDetalle =  request('detail');
		$filtro =  request('filtro');
		$centroCostos = request('centroCosto');
		$investigaciones = DB::Select($sql);
		$totales = DB::Select($sql2);
		$perfil = Auth::user()->roles[0]->id;	
		$error='';
		
        return view('investigaciones.listadoComision', compact('investigaciones', 'title', 'centroCosto', 'totales',  'fecha_inicio', 'fecha_fin','centroCostos','error','perfil','verDetalle','filtro'));
    }


	public function descargarXLScomision(Request $request)
    {
		$fecha_actual = now()->format('Y-m-d');
        $nombre_archivo = 'informeComision_' . $fecha_actual . '.xlsx';
        return Excel::download(new \App\Exports\InformeInvestigacionesComision($request), $nombre_archivo);
	}

	public function descargarXLScomisionResumen(Request $request)
    {
		$fecha_actual = now()->format('Y-m-d');
        $nombre_archivo = 'informeComisionResumen_' . $fecha_actual . '.xlsx';
        return Excel::download(new \App\Exports\InformeInvestigacionesComisionResumen($request), $nombre_archivo);
	}
	

	public function actualizarcomision(Request $request)
    {
		$actualizarOK = true;
		$errores ="";

		if ((request()->has('fecha_comision') && request('fecha_comision') != null) ){
			
			    $sql = 'select investigaciones.id AS idInvestigacion ' .
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_comision ON investigaciones.id = investigaciones_comision.idInvestigacion LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id ' .
				'WHERE  (investigacion_asignacion.Investigador >0 or investigacion_asignacion.Auxiliar>0) and   Investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY)'  ;
			

			$investigaciones = DB::select($sql);
			$fechaComision = request('fecha_comision');
			$tarifaA = TarifasComision::where('tipo', 'AU')->first();
			$tarifaI = TarifasComision::where('tipo', 'IN')->first();
			
			foreach ($investigaciones as $investigacion)
			{
				
				$investigacionAsignacion = InvestigacionAsignacion::where('idInvestigacion', $investigacion->idInvestigacion)->first();
				if ( $tarifaI !=null){
					$comisionCount = InvestigacionesComisiones::where('idInvestigacion', $investigacion->idInvestigacion)->count();
					if ($comisionCount==0 && $investigacionAsignacion->Investigador >0){
						$investigacionComision = InvestigacionesComisiones::create ([
							'idInvestigacion' => ($investigacion->idInvestigacion),
							'FechaComision' => $fechaComision,
							'investigador' => $investigacionAsignacion->Investigador,
							'auxiliar' => $investigacionAsignacion->Auxiliar,
							'tarifaInvestigador' => ( $investigacionAsignacion->Investigador >0 ? $tarifaI->tarifa: null),
							'tarifaAuxiliar'	=> ($investigacionAsignacion->Auxiliar >0 && !($investigacionAsignacion->AuxiliarCompleta >0) ? $tarifaA->tarifa:  ( $investigacionAsignacion->Auxiliar >0 && ($investigacionAsignacion->AuxiliarCompleta >0) ? $tarifaI->tarifa:   null )   )
						]);
					}
					
					else
					{
						$InvestigacionesComisiones= InvestigacionesComisiones::where('idInvestigacion', $investigacion->idInvestigacion)->first();
						$InvestigacionesComisiones->FechaComision =   $fechaComision;
						
						$InvestigacionesComisiones->update(['FechaComision' => $fechaComision ]);
						DB::commit(); 
					}
					
				}
				else {
					$actualizarOK=false;
					$errores = $errores . ' Tarifa No encontrada - ' ; 
				}
			}
			
		}
		else {
			$actualizarOK=false;
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Información actualizada correctamente.' );
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.' . $errores);
		}
		
	}

	

	public function notificarcomisiones(Request $request)
    {
		$actualizarOK = true;
		$errores ="";

		if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin') && request('fecha_fin') != null) ){
			
			$investigadores = request('investigadores') ;
			
			$sql = ' insert into investigaciones_comision (idInvestigacion , created_at , updated_at)  select investigaciones.id,CURRENT_TIMESTAMP(),CURRENT_TIMESTAMP() from investigaciones inner join '.
						' investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id left join ' . 
						' investigaciones_comision on investigaciones_comision.idInvestigacion = investigaciones.id where (investigacion_asignacion.Investigador>0 or investigacion_asignacion.Auxiliar>0 ) and  Investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY) ' .
						' and investigaciones_comision.idInvestigacion is null  ';
				
				DB::insert($sql);
			
			
			foreach ($investigadores as $investigador)
			{
				$InformesInvestigador = InformesInvestigador::where('idInvestigador', $investigador)
										->where('Inicio','>=',request('fecha_inicio'))
										->where('Fin','<=',request('fecha_fin'))
										->first();
				if ($InformesInvestigador == null) {
					
				$InformesInvestigador = InformesInvestigador::create ([
				        'idInvestigador'=> ($investigador),
						'Inicio' =>request('fecha_inicio'),
						'Fin' =>request('fecha_fin'),
						'aceptado'=>0,
						'pagado'=>0,
						'valor'=>0,
					]);
					DB::commit();
					$InformesInvestigador = InformesInvestigador::where('idInvestigador', $investigador)
										->where('Inicio','>=',request('fecha_inicio'))
										->where('Fin','<=',request('fecha_fin'))
										->first();
					
				}
				else {
					
					$InformesInvestigador->Inicio =   request('fecha_inicio');
					$InformesInvestigador->Fin = request('fecha_fin') ;
					
					$InformesInvestigador->update(['Inicio' => request('fecha_inicio') ,'Fin' =>request('fecha_fin') ]);
					DB::commit(); 
					if( !$InformesInvestigador->save()){
						$actualizarOK=false;
						$errores .= 'No se pudo actualizar el informe #' .  $InformesInvestigador->id . '/';
					}
						
				}
				
				
				$sql = ' update investigaciones_comision set idInformeInvestigador = ' .  $InformesInvestigador->id . '  ' .
						'where investigaciones_comision.idInvestigacion in  ( select investigaciones.id from investigaciones inner join '.
						' investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id where investigacion_asignacion.Investigador = ' . $InformesInvestigador->idInvestigador  . 
						' and Investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY))'  .
						' ' ;
				
				DB::update($sql);
				$sql = ' update investigaciones_comision set idInformeAuxiliar = ' .  $InformesInvestigador->id . '  ' .
						'where investigaciones_comision.idInvestigacion in  ( select investigaciones.id from investigaciones inner join '.
						' investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id where investigacion_asignacion.Auxiliar = ' . $InformesInvestigador->idInvestigador  . 
						' and Investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY ))'  .
						' ' ;
				
				DB::update($sql);
				$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$signature = substr(str_shuffle($caracteres), 0, 64);
				if ($InformesInvestigador->aceptado ==0){
					$Investigador = User::where('id', $InformesInvestigador->idInvestigador)->first();
					$data = [
						'investigador' => $Investigador, 
						'informe' => $InformesInvestigador, 
						'fecha_inicio' => request('fecha_inicio'), 
						'fecha_fin' => request('fecha_fin'),
						'signature' => $signature
						
					];
					if ($Investigador!=null){
						try {
							Mail::to($Investigador->email)->bcc('coordinador.campo.colp@jahvmcgregor.com.co')->send(new NotificarInforme($data));
						}
						catch (\Exception  $e) {
							$actualizarOK=false;
							 $errores =  $errores . 'Error al notificar a '. $Investigador->email . '. \n*' .  $e->getMessage() . '\n*.'; 
						}
						//Mail::to('especialista.db.colp@jahvmcgregor.com.co')->send(new NotificarInforme($data));
					}
				}

			}	
				
		}
		else {
			$actualizarOK=false;
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Informes enviados para revision de los usuarios' );
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo notificar.' . $errores);
		}
		
	}
				
		
		
    public function verinformeInvestigador(string $id)
    {
		$InformesInvestigador = InformesInvestigador::where('id', $id)->first();
										
		$Investigador = User::where('id', $InformesInvestigador->idInvestigador)->first();
		
        $title = 'Revisión de Informe Investigador : ' . strtoupper($Investigador->name) . ' ' . strtoupper($Investigador->lastname) . '. Periodo : ' . $InformesInvestigador->Inicio . ' a ' . $InformesInvestigador->Fin . '.' ;

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
				'investigaciones_comision.doble, ' .
				'investigaciones_comision.AuxiliarCompleta, ' .
				'investigaciones_comision.porBeneficiario, ' .
				'if(investigaciones_facturacion.FechaFacturacion is null,0,1 ) AS facturado, ' .
				'investigaciones_facturacion.FechaRadicacion, ' .
				' DATE_FORMAT( investigaciones_facturacion.FechaFacturacion, "%Y-%m-%d") as FechaFacturacion, ' .
				'investigaciones_facturacion.idTarifa, ' .
				'investigaciones_comision.FechaComision, ' .
				'investigaciones_facturacion.idEstadoFacturacion, ' .
				'(case when investigacion_asignacion.Investigador is null or investigacion_asignacion.Investigador=0 then 0 else comision_investigador.tarifa end )*(case when investigaciones_comision.porBeneficiario>0 then numeroBeneficiarios when investigaciones_comision.doble>0 then 2   else 1 end ) as comision_investigador, ' .
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
				'WHERE investigaciones_comision.idInformeInvestigador = ' . $id . ' or investigaciones_comision.idInformeAuxiliar = ' . $id ;
		$investigaciones = DB::select($sql);
		$perfil = Auth::user()->roles[0]->id;	
		$usuario = Auth::user();
		$error='';
		if ((( $usuario->id == $Investigador->id)||$perfil==1) && $InformesInvestigador->aceptado ==0 )
			return view('investigaciones.listadoComisionInvestigador', compact('investigaciones', 'title', 'error','perfil','id'));
		else
			return view('investigaciones.denegado');
		
    }		
		
		


	public function aceptarinforme(Request $request)
    {
		$actualizarOK = true;
		$errores ="";
		
		$InformesInvestigador = InformesInvestigador::where('id', request('id'))->first();
		
		if ($InformesInvestigador->aceptado ==0){
			$valorTotal=0;
			
			$Investigador = User::where('id', $InformesInvestigador->idInvestigador)->first();
			$sql = 'select investigaciones.id AS idInvestigacion, ' .
				'investigaciones_comision.id as idComision, ' .
				'investigaciones_comision.idInformeInvestigador, ' .
				'investigaciones_comision.idInformeAuxiliar, ' .
				'(case when investigacion_asignacion.Investigador is null or investigacion_asignacion.Investigador=0 then 0 else comision_investigador.tarifa end )*(case when investigaciones_comision.porBeneficiario>0 then numeroBeneficiarios when investigaciones_comision.doble>0 then 2   else 1 end ) as comision_investigador, ' .
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
				'WHERE investigaciones_comision.idInformeInvestigador = ' . $InformesInvestigador->id . ' or investigaciones_comision.idInformeAuxiliar = ' . $InformesInvestigador->id ;
			$investigaciones = DB::select($sql);

			foreach ($investigaciones as $investigacion)
			{				
				$InvestigacionesComisiones = InvestigacionesComisiones::where('id', $investigacion->idComision)->first();
				
				if ($InvestigacionesComisiones->idInformeInvestigador == $InformesInvestigador->id  ){
					$valorTotal	+= $investigacion->comision_investigador;
					$InvestigacionesComisiones->ValorInvestigador =   $investigacion->comision_investigador;
					$InvestigacionesComisiones->update(['ValorInvestigador' => $investigacion->comision_investigador  ]);
				}
				else
					if ($InvestigacionesComisiones->idInformeAuxiliar == $InformesInvestigador->id  )
					{
						$valorTotal	+= $investigacion->comision_auxiliar;
						$InvestigacionesComisiones->ValorAuxiliar =   $investigacion->comision_auxiliar;
						$InvestigacionesComisiones->update(['ValorAuxiliar' =>$investigacion->comision_auxiliar ]);
					}
				
				DB::commit(); 
				
			}			
			
			$InformesInvestigador->aceptado =   1;
			$InformesInvestigador->observaciones = request('Observaciones') ;
			$InformesInvestigador->valor = $valorTotal	;
			$InformesInvestigador->update(['aceptado' => 1 ,'observaciones' =>request('Observaciones'), 'valor' => $valorTotal  ]);
			DB::commit(); 
			if( !$InformesInvestigador->save()){
				$actualizarOK=false;
				$errores .= 'No se pudo actualizar el informe #' .  $InformesInvestigador->id . '/';
			}
			$caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				$signature = substr(str_shuffle($caracteres), 0, 64);
			$data = [
				'investigador' => $Investigador, 
				'informe' => $InformesInvestigador, 
				'signature' => $signature
				];

			
				
			
			if ($Investigador!=null){
				Mail::to($Investigador->email)->cc('coordinador.campo.colp@jahvmcgregor.com.co')->send(new NotificarInformeAprobado($data));
				//Mail::to('especialista.db.colp@jahvmcgregor.com.co')->send(new NotificarInformeAprobado($data));
			}
		}
		
		
		if ($actualizarOK) {
			return view('investigaciones.aprobado');
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.' . $errores);
		}
		
		
		
	}


	public function actualizarDoble(string $id, int $establece)
    {
		$actualizarOK = true;
		$errores ="";
		
		if ($id !='' && $establece >=0 ){
			$comisionCount = InvestigacionesComisiones::where('idInvestigacion', $id)->count();
			if ($comisionCount==0 ){
				$investigacionComision = InvestigacionesComisiones::create ([
					'idInvestigacion' => ($id),
					'doble' => ($establece >0 ? true : false),
					
				]);
			}
			else{
				$InvestigacionesComisiones = InvestigacionesComisiones::where('idInvestigacion', $id)->first();

				$InvestigacionesComisiones::find($InvestigacionesComisiones->id);
				if ($InvestigacionesComisiones->tarifaInvestigador==0)
				{				
					$InvestigacionesComisiones->doble =  $establece;
					$InvestigacionesComisiones->update(['doble' => $establece ]);
					if( !$InvestigacionesComisiones->save()){
						$actualizarOK=false;
					}				
				}
				else 
				{
					$actualizarOK=false;
					$errores= 'No se puede actualizar. Comision ya registrada.';
				}
				
			}
			
				
			
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Información actualizada correctamente.' );
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.' . $errores);
		}
		
	}
	
	

	public function actualizarPorBeneficiario(string $id, int $establece)
    {
		$actualizarOK = true;
		$errores ="";
		
		if ($id !='' && $establece >=0 ){
			$comisionCount = InvestigacionesComisiones::where('idInvestigacion', $id)->count();
			if ($comisionCount==0 ){
				$investigacionComision = InvestigacionesComisiones::create ([
					'idInvestigacion' => ($id),
					'porBeneficiario' => ($establece >0 ? true : false),
					
				]);
			}
			else{
				$InvestigacionesComisiones = InvestigacionesComisiones::where('idInvestigacion', $id)->first();

			
				$InvestigacionesComisiones->porBeneficiario =  $establece;
				$InvestigacionesComisiones->update(['porBeneficiario' => ($establece >0 ? true : false) ]);
				if( !$InvestigacionesComisiones->save()){
					$actualizarOK=false;
				}				
				
				
			}
			
				
			
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Información actualizada correctamente.' );
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.' . $errores);
		}
		
	}
	
	
	public function actualizarAuxiliar(string $id, int $establece)
    {
		$actualizarOK = true;
		$errores ="";

		if ($id !='' && $establece >=0 ){
			$comisionCount = InvestigacionesComisiones::where('idInvestigacion', $id)->count();
			if ($comisionCount==0 ){
				$investigacionComision = InvestigacionesComisiones::create ([
					'idInvestigacion' => ($id),
					'AuxiliarCompleta' => ($establece >0 ? true : false),
					
				]);
			}
			else{
				$InvestigacionesComisiones = InvestigacionesComisiones::where('idInvestigacion', $id)->first();

				if ($InvestigacionesComisiones->tarifaInvestigador==0)
				{	
					$InvestigacionesComisiones::find($InvestigacionesComisiones->id);
					$InvestigacionesComisiones->AuxiliarCompleta =  $establece;
					$InvestigacionesComisiones->update(['AuxiliarCompleta' => $establece ]);
					if( !$InvestigacionesComisiones->save()){
						$actualizarOK=false;
						
					}	
				}				
				else 
				{
					$actualizarOK=false;
					$errores= 'No se puede actualizar. Comision ya registrada.';
				}				
				
			}
			
				
			
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Información actualizada correctamente.' );
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.' . $errores);
		}
		
	}
				
	
	
    public function descargarZIPInformesAprobados(Request $request)
    {
		$zip = new ZipArchive;
		
		
		
		$fecha_actual = now()->format('Y-m-d');
        $nombre_archivo = 'InformesAprobados' . $fecha_actual . '.zip';
		
		
		 $title = 'Informes Aprobados';

        $sql = 'SELECT   ucase(CONCAT_WS(\' \',Investigador.name , Investigador.lastname)) as nombre,' .
			'informes_investigador.id AS idInforme , informes_investigador.valor ' .	
			'FROM informes_investigador INNER JOIN ' .
			'users as Investigador ON Investigador.id = informes_investigador.idInvestigador ' .
			'WHERE informes_investigador.aceptado >0  and informes_investigador.Inicio  >=  \'' . request('fecha_inicio') . '\' and informes_investigador.Fin <=\'' . request('fecha_fin') . '\' ' ;
				
		$informes = DB::Select($sql);
		$fileCreated =false;
		$fileOut ='';
		$GetPDF = new PDFController();
		if ($zip->open(public_path($nombre_archivo), ZipArchive::CREATE) === TRUE) {
			foreach ($informes as $informe)
			{
				$filename = 'informe' .  $informe->idInforme . '-' . $informe->nombre . '.pdf';
				$pdf = $GetPDF->renderInforme($informe->idInforme);
				$pdf->save(storage_path('app/informes/' . $filename));
				$zip->addFile(storage_path('app/informes/' . $filename), $filename);

			}
		$fileCreated =true;

				

				
		}
		$zip->close();
		if ($fileCreated){
				return response()->download(public_path($nombre_archivo))->deleteFileAfterSend(true);
        } else {
			return back()->with('infoError', 'No se pudo generar el archivo.');
            
        }
	}
	
	
	
}
