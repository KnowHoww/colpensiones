<?php

namespace App\Http\Controllers;

use App\Models\Investigaciones;
use App\Models\InvestigacionesFacturacion;
use App\Models\EstadoRadicacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class InvestigacionesRadicacionController extends Controller
{
    
    public function index(){
        $servicios = Servicios::all();
        
        return view('home',compact('servicios'));
    }
	
	public function filtros(){
		$title = 'Investigaciones pendientes de radicación';
        
        return view('investigaciones.radicacion',compact('title'));
    }
	

    public function buscarInvestigacionesRadicacion(Request $request)
    {
        $title = 'Investigaciones pendientes de radicación';

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
				'states.name AS estado, investigaciones.estado as idEstado, ' .
				'if((investigaciones_facturacion.FechaRadicacion is null and investigaciones.estado =7) or (investigaciones_facturacion.FechaCorrecionRadicacion is null and investigaciones.estado =16),1,0 ) AS radicado, ' .
				'investigaciones_facturacion.FechaRadicacion, ' .
				'investigaciones_facturacion.radicador, ' .
				'investigaciones_facturacion.facturador, ' .
				'investigaciones_facturacion.FechaCorrecionRadicacion, ' .
				'investigaciones_facturacion.idEstadoRadicacion, ' .
				'investigaciones_facturacion.idEstadoFacturacion, ' .
				'radicador.name AS radicador, ' .
				'facturador.name AS facturador ' .
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
				'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
				'states ON investigaciones.estado = states.id LEFT JOIN ' .
				'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
				'users as Analista ON Analista.id = investigacion_asignacion.Analista LEFT JOIN ' .
				'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  LEFT JOIN ' .
				'users as radicador ON radicador.id = investigaciones_facturacion.radicador LEFT JOIN ' .
				'users as facturador  ON facturador.id = investigaciones_facturacion.facturador  ' .
				'WHERE not investigaciones.FechaFinalizacion IS null  and  investigaciones.estado  in (7,16) ' ;

	
		if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $sql = $sql . ' and ((investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY) ) or (investigaciones.FechaFinalizacionObjecion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY) )) '  ;
           
        }		
		$estadosRadicacion = EstadoRadicacion::all();
		

		$sql = $sql . ' ORDER BY radicado desc, investigaciones.updated_at; '; 
		
		$fecha_inicio = request('fecha_inicio');
		$fecha_fin =  request('fecha_fin');
		
		$investigaciones = DB::Select($sql);
			
		$cantidad = count($investigaciones);
		$cantidadPendientes = 0;
		//$valorFacturados = 0;
		foreach ($investigaciones as $lineaTotal){
			$cantidadPendientes = $cantidadPendientes + $lineaTotal->radicado;
		//	$valorFacturados = $valorFacturados + $lineaTotal->facturado;
		}
			//$cantidadFacturados = count($investigaciones2);
		$totalreporte =0;
		
        return view('investigaciones.listadoRadicacion', compact('investigaciones', 'title', 'cantidad', 'cantidadPendientes', 'fecha_inicio', 'fecha_fin','estadosRadicacion'));
    }



    public function descargarZIPRadicacion(Request $request)
    {
		$zip = new ZipArchive;
		
		
		
		$fecha_actual = now()->format('Y-m-d');
        $nombre_archivo = 'CasosPorRadicar' . $fecha_actual . '.zip';
		
		
		 $title = 'Investigaciones pendientes de radicación';

        $sql = 'select investigaciones.id AS id, investigaciones.estado ' .
				'FROM investigaciones LEFT JOIN ' .
				'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion ' .
				
				'WHERE not investigaciones.FechaFinalizacion IS null  and  ((investigaciones.estado=7 and investigaciones_facturacion.FechaRadicacion is null ) or (investigaciones.estado=16 and investigaciones_facturacion.FechaCorrecionRadicacion is null )) ' ;

	
		if ((request()->has('fecha_inicio') && request('fecha_inicio') != null) && (request()->has('fecha_fin')  && request('fecha_fin') != null)) {
            $sql = $sql . ' and ((investigaciones.FechaFinalizacion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY) ) or (investigaciones.FechaFinalizacionObjecion  between  \'' . request('fecha_inicio') . '\' and DATE_ADD(\'' . request('fecha_fin') . '\', INTERVAL 1 DAY) )) '  ;
           
        }		
		
		$fecha_inicio = request('fecha_inicio');
		$fecha_fin =  request('fecha_fin');
		
		$investigaciones = DB::Select($sql);
		$fileCreated =false;
		$fileOut ='';
		if ($zip->open(public_path($nombre_archivo), ZipArchive::CREATE) === TRUE) {
			foreach ($investigaciones as $investigacion)
			{
				$invest = Investigaciones::where ('id' , $investigacion->id)->first();
				$fileOut = $fileOut . $invest->nombreCarpeta . ':';
				if ($invest->estado == 7 ){
					$documentos = Storage::disk('investigaciones')->allFiles('finalizados/' . $invest->nombreCarpeta);
				}
				else {
					$documentos = Storage::disk('investigaciones')->allFiles('finalizadosObjetados/' . $invest->nombreCarpeta);
				}
				foreach ($documentos as $file) {
					$fileOut = $fileOut . $file  . '//';
					$filename = "investigaciones/" . $file ;
				/*	if ($invest->estado == 7 ){
						
					}
					else {
						$documentos = "/investigaciones/finalizadosObjetado/" . $file ;
					}*/
					$fileCreated=true;
					$zip->addFile($filename, $filename);
				}

				
			}
			$zip->close();
			if ($fileCreated)
				return response()->download(public_path($nombre_archivo))->deleteFileAfterSend(true);
			else
				return back()->with('infoError', 'Archivo no se genera con información.' . $fileOut );
        } else {
			return back()->with('infoError', 'No se pudo generar el archivo.');
            
        }
		
       
	}
	public function actualizarRadicados(Request $request)
    {
		$actualizarOK = true;
		$errores ="";
		if (request()->has('fecha_radicacion') && request('fecha_radicacion') != null){
			$investigaciones = request('investigaciones');
			$fechaRadicacion = request('fecha_radicacion');
			foreach ($investigaciones as $investigacion)
			{
				$investiga = Investigaciones::where('id', $investigacion)->first();
				$InvestigacionFacturacion = InvestigacionesFacturacion::where('idInvestigacion', $investigacion)->first();
				if ($InvestigacionFacturacion==null){
					$InvestigacionFacturacion = InvestigacionesFacturacion::create ([
						'idInvestigacion' => ($investigacion),
						'FechaRadicacion' => $fechaRadicacion ,
						'radicador' =>  Auth::user()->id,
						'idEstadoRadicacion' =>  3,
						'idEstadoFacturacion' =>  1
					]);
				}
				else {
					if ($investiga->estado == 7){
						$InvestigacionFacturacion->FechaRadicacion =  $fechaRadicacion;
					}
					else{
						$InvestigacionFacturacion->FechaCorrecionRadicacion =  $fechaRadicacion;
					}
					$InvestigacionFacturacion->radicador =  Auth::user()->id;
					$InvestigacionFacturacion->idEstadoRadicacion =  3;
					
					 
					if( !$InvestigacionFacturacion->save()){
						$actualizarOK=false;
						$errores = $errores . $investigacion .' - ' ; 
					}
				}
				 
				
			}
			
		}
		else {
			if (request()->has('estadosRadicacion') && request('estadosRadicacion') != null && request('estadosRadicacion') != '' ){
				$investigaciones = request('investigaciones');
				$estadoActualizar = request('estadosRadicacion');
				$fechaRadicacion = null;
				if ( request()->has('fecha_radicacion') && request('fecha_radicacion') != null && $estadoActualizar ==3){
					$fechaRadicacion=request('fecha_radicacion');
				}
				foreach ($investigaciones as $investigacion)
				{
					$investiga = Investigaciones::where('id', $investigacion)->first();
					$InvestigacionFacturacion = InvestigacionesFacturacion::where('idInvestigacion', $investigacion)->first();
					if ($estadoActualizar == 3) {
						if ($InvestigacionFacturacion==null){
							$InvestigacionFacturacion = InvestigacionesFacturacion::create ([
								'idInvestigacion' => ($investigacion),
								'FechaRadicacion' => $fechaRadicacion ,
								'radicador' =>  Auth::user()->id,
								'idEstadoRadicacion' =>  $estadoActualizar,
								'idEstadoFacturacion' =>  1
							]);
						}
						else {
							if ($investiga->estado == 7){
								$InvestigacionFacturacion->FechaRadicacion =  $fechaRadicacion;
							}
							else{
								$InvestigacionFacturacion->FechaRadicacionCorrecion =  $fechaRadicacion;
							}
							$InvestigacionFacturacion->radicador =  Auth::user()->id;
							$InvestigacionFacturacion->idEstadoRadicacion =  $estadoActualizar;
							if( !$InvestigacionFacturacion->save()){
								$actualizarOK=false;
								$errores = $errores . $investigacion .' - ' ; 
							}
						}
					}
					else {
						if ($InvestigacionFacturacion==null){
							$InvestigacionFacturacion = InvestigacionesFacturacion::create ([
								'idInvestigacion' => ($investigacion),
								'radicador' =>  Auth::user()->id,
								'idEstadoRadicacion' =>  $estadoActualizar,
								'idEstadoFacturacion' =>  1
							]);
						}
						else {
							$InvestigacionFacturacion->radicador =  Auth::user()->id;
							$InvestigacionFacturacion->idEstadoRadicacion =  $estadoActualizar;
							if( !$InvestigacionFacturacion->save()){
								$actualizarOK=false;
								$errores = $errores . $investigacion .' - ' ; 
							}
						}
						
					}
					
					
				}
				
			}
			else {
				$actualizarOK=false;
			}
		}
		
		if ($actualizarOK) {
			return back()->with('info', 'Información actualizada correctamente.');
		} else {
			return back()->with('infoError', 'Verifique la información. No se pudo actualizar.' . $errores);
		}
		
	}
}
