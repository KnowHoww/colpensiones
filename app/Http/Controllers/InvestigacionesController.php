<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log; 
use App\Models\CentroCostos;
use App\Models\ControlDiasFestivos;
use App\Models\Departamento;
use App\Models\DetalleRiesgo;
use App\Models\InvestigacionAcreditacion;
use App\Models\InvestigacionAsignacion;
use App\Models\InvestigacionAuxilioFunerario;
use App\Models\InvestigacionConsultasAntecedentesBeneficiarios;
use App\Models\InvestigacionConsultasAntecedentesCausante;
use App\Models\InvestigacionEntrevistaFamiliares;
use App\Models\Investigaciones;
use App\Models\InvestigacionesBeneficiarios;
use App\Models\investigacionesObservacionesEstado;
use App\Models\InvestigacionesValidacionDocumentalCausante;
use App\Models\InvestigacionGastosVivienda;
use App\Models\InvestigacionLaborCampo;
use App\Models\InvestigacionEntrevistaSolicitante;
use App\Models\InvestigacionEscolaridad;
use App\Models\InvestigacionEstudiosAuxiliares;
use App\Models\InvestigacionFraude;
use App\Models\InvestigacionRegion;
use App\Models\InvestigacionValidacionDocumentalBeneficiarios;
use App\Models\InvestigacionVerificacion;
use App\Models\Juntas;
use App\Models\actividadestipoinvestigacion;
use App\Models\Municipio;
use App\Models\Novedad;
use App\Models\Notificaciones;
use App\Models\Parentesco;
use App\Models\SeccionesFormulario;
use App\Models\Servicios;
use App\Models\States;
use App\Models\TipoDocumento;
use App\Models\TipoInvestigacion;
use App\Models\TipoObjecion;
use App\Models\TipoPension;
use App\Models\TipoPrioridad;
use App\Models\TipoRiesgo;
use App\Models\TipoSolicitante;
use App\Models\TipoSolicitud;
use App\Models\TipoTramite;
use App\Models\TrazabilidadActividadesRealizadas;
use App\Models\User;
use App\Models\generarDocumentacion;
use Carbon\Carbon;
use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Smalot\PdfParser\Parser;
use App\Models\Documento; 




class InvestigacionesController extends Controller
{
    protected $pdfGenerator;

    public function __construct(PDFController $pdfGenerator)
    {
        $this->middleware('can:investigaciones.view')->only('index');
        $this->middleware('can:colpensiones.investigaciones.view')->only('show');
        $this->middleware('can:investigaciones.view.btn-create')->only('create', 'store');
        /* $this->middleware('can:investigaciones.view.btn-edit')->only('edit', 'update'); */
        $this->pdfGenerator = $pdfGenerator;
    }

    public function esFestivo($start_date, $end_date)
    {
        $festiveDates = ControlDiasFestivos::pluck('fecha')->toArray();
        $newEndDate = $end_date;
        while ($start_date->lte($end_date)) {
            if (in_array($start_date->format('Y-m-d'), $festiveDates) || $start_date->isWeekend()) {
                $newEndDate->addDay();
            }
            $start_date->addDay();
        }

        return $newEndDate;
    }

    public function buscarInvestigacion(Request $request)
    {
        $title = 'Historial de todas las investigaciones';

        $estados = States::all();

        $fechaLimite = true;
        $fechaFinalizacion = true;
        $fechaCancelacion = true;
        $btnInformePdf = true;
        $creador = true;
        $aprobador = true;
        $fechaAprobacion = true;
        $coordinador = true;
        $investigador = true;
        $btneditar = true;
        $btnver = true;
        $btnrevision = false;
        $btninforme = true;
        $fechaObjecion = true;
        $btninformeObjetado = true;

        $q = $request->input('filtro');
        $investigaciones = Investigaciones::select(
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
            'states.name as fraude',
            'estadoInvestigacion.name as estadoInvestigacion',
            'investigacion_acreditacion.acreditacion as acreditacion',

        )
            ->leftJoin('investigacion_fraude', 'investigacion_fraude.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('investigacion_acreditacion', 'investigacion_acreditacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('states', 'investigacion_fraude.fraude', '=', 'states.id')
            ->leftJoin('states as estadoInvestigacion', 'investigaciones.estado', '=', 'estadoInvestigacion.id')
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
            ->leftJoin('users as analista', 'analista.id', '=', 'investigacion_asignacion.Analista')
            ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
            ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
            ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
			->leftJoin('colpensiones.investigaciones_beneficiarios', 'colpensiones.investigaciones_beneficiarios.IdInvestigacion', '=', 'investigaciones.id')
            ->where(function($query) use ($q) {
                $query->where('estadoInvestigacion.name', 'like', "%$q%")
                    ->orWhere('investigaciones.id', 'like', "%$q%")
                    ->orWhere('investigaciones.IdCase', 'like', "%$q%")
                    ->orWhere('investigaciones.ciudad', 'like', "%$q%")
                    ->orWhere('investigaciones.NumeroRadicacionCaso', 'like', "%$q%")
                    ->orWhere('analistaColpensiones.name', 'like', "%$q%")
                    ->orWhere('analistaColpensiones.lastname', 'like', "%$q%")
                    ->orWhere('coordinador.name', 'like', "%$q%")
                    ->orWhere('coordinador.lastname', 'like', "%$q%")
                    ->orWhere('investigador.name', 'like', "%$q%")
                    ->orWhere('investigador.lastname', 'like', "%$q%")
                    ->orWhere('aprobadorColpensiones.name', 'like', "%$q%")
                    ->orWhere('aprobadorColpensiones.lastname', 'like', "%$q%")
                    ->orWhere('investigaciones.NumeroDeDocumento', 'like', "%$q%")
                    ->orWhere('investigaciones.PrimerNombre', 'like', "%$q%")
                    ->orWhere('investigaciones.SegundoNombre', 'like', "%$q%")
                    ->orWhere('investigaciones.PrimerApellido', 'like', "%$q%")
                    ->orWhere('investigaciones.SegundoApellido', 'like', "%$q%")
                    ->orWhere('investigaciones.CasoPadreOriginal', 'like', "%$q%")
                    ->orWhere('colpensiones.investigaciones_beneficiarios.NumeroDocumento', 'like', "%$q%");
            })
            ->distinct('investigaciones.id')
			->with(['estados', 'tipoInvestigaciones', 'tipoRiesgos', 'detalleRiesgos', 'tipoTramites', 'tipoSolicitudes', 'tipoSolicitantes', 'tipoPensiones', 'tipoDocumentos', 'prioridades'])
            ->orderBy('investigaciones.id', 'desc')
            ->paginate(1000);        
            return view('investigaciones.listado', compact('btninformeObjetado', 'estados', 'investigaciones', 'q', 'title', 'fechaObjecion', 'fechaAprobacion', 'fechaLimite', 'fechaFinalizacion', 'fechaCancelacion', 'btnver', 'btnrevision', 'btninforme', 'btneditar'));
    }
    /**
     * Almacena un recurso recién creado en el almacenamiento.
     */
    public function store(Request $request)
    {
        $nombreVariable = 'InstitucionEducativa_';
        $datosRequest = $request->all();
        

        $request->validate([
            'NumeroRadicacionCaso' => 'required|string|max:50',
            /* 'CasoPadreOriginal' => 'required|string|max:50', */
            'TipoInvestigacion' => 'required|string|max:2',
            'TipoRiesgo' => 'required|string|max:4',
            'DetalleRiesgo' => 'required|string|max:4',
            'TipoDocumento' => 'required|string|max:3',
            'NumeroDeDocumento' => 'required|integer',
            'PrimerNombre' => 'required|string|max:50',
            'PrimerApellido' => 'required|string|max:50',
        ]);

        $fechaSolicitud = now()->timezone('America/Bogota');
        $tipo = TipoInvestigacion::where('codigo', $request->TipoInvestigacion)->first();
        $fechaEntrega = $fechaSolicitud->copy()->addDays($tipo->TiempoEntrega);
        $fechaLimite = $this->esFestivo(Carbon::createFromFormat('Y-m-d', $fechaSolicitud->toDateString()), Carbon::createFromFormat('Y-m-d', $fechaEntrega->toDateString()));
        $centroDeCosto = CentroCostos::find(Auth::user()->centroCosto);
        try {
            DB::beginTransaction();
            //$validacion =  DB::table('investigaciones')->where('CasoPadreOriginal', $request->CasoPadreOriginal)->get()->count();
            $validacion = true;
            if ($validacion) {
                $investigacion = Investigaciones::create([
                    "MarcaTemporal" => $fechaSolicitud,
                    "NumeroRadicacionCaso" => $request->NumeroRadicacionCaso,
                    "CasoPadreOriginal" => $request->NumeroRadicacionCaso,
                    "TipoInvestigacion" => $request->TipoInvestigacion,
                    "TipoRiesgo" => $request->TipoRiesgo,
                    "DetalleRiesgo" => $request->DetalleRiesgo,
                    "TipoTramite" => $request->TipoTramite,
                    "TipoSolicitud" => $request->TipoSolicitud,
                    "TipoSolicitante" => $request->TipoSolicitante,
                    "TipoPension" => $request->TipoPension,
                    "TipoDocumento" => $request->TipoDocumento,
                    "NumeroDeDocumento" => $request->NumeroDeDocumento,
                    "PrimerNombre" => $request->PrimerNombre,
                    "SegundoNombre" => $request->SegundoNombre,
                    "PrimerApellido" => $request->PrimerApellido,
                    "SegundoApellido" => $request->SegundoApellido,
                    "Ciudad" => $request->Ciudad,
                    "DireccionCausante" => $request->DireccionCausante,
                    "TelefonoCausante" => $request->TelefonoCausante,
                    "estado" => 17,
                    "analista" => Auth::user()->id,
                    "Junta" => $request->Junta,
                    "NumeroDictamen" => $request->NumeroDictamen,
                    "FechaDictamen" => $request->FechaDictamen,
                    "Prioridad" => 1,
                    "Observacion" => $request->Observacion,
                    "PuntoAtencion" => $request->PuntoAtencion,
                    "DireccionPunto" => $request->DireccionPunto,
                    "FechaLimite" => $fechaLimite,
                    "CentroCosto" => $centroDeCosto->codigo,
                    "ciudadRegion" => $request->ciudadRegion,
                    "departamentoRegion" => $request -> departamentoRegion,
                ]);

                InvestigacionAsignacion::create([
                    'idInvestigacion' => $investigacion->id,
                ]);

                investigacionesObservacionesEstado::create([
                    'idInvestigacion' => $investigacion->id,
                    'idUsuario' => Auth::user()->id,
                    'idEstado' => 17,
                    'observacion' => 'Se solicita la aprobación de la investigación creada.'
                ]);

                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->IdCase = $investigacion->id;
                $nombreCarpeta = $actualizacion->NumeroRadicacionCaso . '_' . $investigacion->id;
                $actualizacion->nombreCarpeta = $nombreCarpeta;
                $actualizacion->update($request->all());
                // $cant_beneficiarios = count(array_filter($datosRequest, function ($key) use ($nombreVariable) {
                //     return strpos($key, $nombreVariable) === 0;
                // }, ARRAY_FILTER_USE_KEY));
                $beneficiarios = $request->input('beneficiarios', []); 
    
                foreach ($beneficiarios as $datos) {
                    if (!empty($datos['NumeroDocumento'])) { 
                        $nitBeneficiario = empty($datos['Nit'])? "":$datos['Nit'];
                        $institucionEducativaBeneficiario =  empty($datos['InstitucionEducativa'])?  "":$datos['InstitucionEducativa'];
                        InvestigacionesBeneficiarios::create([
                            'IdInvestigacion' => $investigacion->id,
                            'TipoDocumento' =>  $datos['TipoDocumento'],
                            'NumeroDocumento' => $datos['NumeroDocumento'],
                            'PrimerNombre' => $datos['PrimerNombre'],
                            'SegundoNombre' => $datos['SegundoNombre'],
                            'PrimerApellido' => $datos['PrimerApellido'],
                            'SegundoApellido' => $datos['SegundoApellido'],
                            'Parentesco' => $datos['Parentesco'],
                            'Nit' => $nitBeneficiario,
                            'InstitucionEducativa' => $institucionEducativaBeneficiario,
                        ]);
                    }
                }
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $nombreArchivo = $file->getClientOriginalName();
                        Storage::disk('investigaciones')->putFileAs('/radicado/' . $nombreCarpeta, $file, $nombreArchivo);
                    }
                }
                DB::commit();
                return redirect('investigacionesTodas/17')->with('info', 'Se ha creado la investigación #' . $investigacion->id);
            } else {
                return redirect()->route('investigacion.create')->withErrors('info', 'El número radicado ya existe');
            }
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            echo $e;
            DB::rollback();

            // Manejar el error, registrar o notificar según sea necesario
            throw $e;
        }
    }

    public function consultaValidacionInvestigacion(Request $request)
    {
        $Historial = Investigaciones::where('TipoDocumento', $request->TipoDocumento)->where('NumeroDeDocumento', $request->NumeroDeDocumento)->get();
        $informacion = $request;
        $TipoInvestigacion = TipoInvestigacion::all();
        $TipoRiesgo = TipoRiesgo::where('codigo','like',"RC%")->get();
        $TipoPension = TipoPension::all();
        $TipoTramite = TipoTramite::all();
        $DetalleRiesgo = DetalleRiesgo::all();
        $TipoSolicitud = TipoSolicitud::all();
        $TipoSolicitante = TipoSolicitante::all();
        $TipoDocumento = TipoDocumento::all();
        $TipoPrioridad = TipoPrioridad::all();
        $TipoParentesco = Parentesco::all();
        $TipoJuntas = Juntas::all();
        $departamentos = Departamento::all();
        $municipios = Municipio::all();
        return view('investigaciones.create', compact('TipoJuntas', 'departamentos','municipios','informacion', 'Historial', 'TipoInvestigacion', 'TipoRiesgo', 'TipoPension', 'TipoTramite', 'DetalleRiesgo', 'TipoSolicitud', 'TipoSolicitante', 'TipoDocumento', 'TipoPrioridad', 'TipoParentesco', 'TipoTramite'));
    }

    public function investigacionesTrazabilidad($data = null)
    {
        $title = 'Trazabilidad de investigaciones';
        $servicios = Servicios::all();
        $investigaciones = Investigaciones::select(
            'investigaciones.*',
            'investigador.name as name_investigador',
            'investigador.lastname as lastname_investigador',
            'coordinador.name as name_coordinador',
            'coordinador.lastname as lastname_coordinador',
            'analistaColpensiones.name as name_analistaColpensiones',
            'analistaColpensiones.lastname as lastname_analistaColpensiones',
            'aprobadorColpensiones.name as name_aprobadorColpensiones',
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones',
            'states.name as fraude',
            'investigacion_acreditacion.acreditacion',
            'investigacion_acreditacion.idBeneficiario',
            'investigacion_acreditacion.acreditacion',
        )
            ->leftJoin('investigacion_fraude', 'investigacion_fraude.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('investigacion_acreditacion', 'investigacion_acreditacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('states', 'investigacion_fraude.fraude', '=', 'states.id')
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
            ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
            ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
            ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
            ->orderBy('investigaciones.id', 'desc')
            ->distinct('investigacion_fraude.idInvestigacion');

        $data = explode('&', $data);
        if (isset($data[0])) {
            switch ($data[0]) {
                case 'CV':
                    $title = 'Trazabilidad investigaciones de convivencia';
                    $filtro = 'CV';
                    $investigaciones->where('TipoInvestigacion', $filtro);
                    break;
                case 'DE':
                    $title = 'Trazabilidad investigaciones de dependencia';
                    $filtro = 'DE';
                    $investigaciones->where('TipoInvestigacion', $filtro);
                    break;
                case 'VD':
                    $title = 'Trazabilidad investigaciones de validación';
                    $filtro = 'VD';
                    $investigaciones->where('TipoInvestigacion', $filtro);
                    break;
                case 'ES':
                    $title = 'Trazabilidad investigaciones de escolaridad';
                    $filtro = 'ES';
                    $investigaciones->where('TipoInvestigacion', $filtro);
                    break;
                case 'VAF':
                    $title = 'Trazabilidad investigaciones de Auxilio';
                    $filtro = 'VAF';
                    $investigaciones->where('TipoInvestigacion', $filtro);
                    break;
                default:
                    $title = 'Trazabilidad investigaciones';
                    $filtro = '';
                    break;
            }
        }

        if (isset($data[1])) {
            $consultaEstado = explode('=', $data[1]);
            if (isset($consultaEstado)) {
                if ($consultaEstado[0] == 'est') {
                    $investigaciones->where('investigaciones.estado', $consultaEstado[1]);
                }
            }
        }

        $investigaciones = $investigaciones->paginate(10);
        return view('investigaciones.trazabilidad', compact('investigaciones', 'title', 'servicios', 'filtro'));
    }

    /* public function indexColpensionesTabs()
    {
        $centro = CentroCostos::where('id', Auth::user()->centroCosto)->pluck('codigo');
        $title = 'Trazabilidad de investigaciones colpensiones';
        $investigaciones = Investigaciones::select(
            'investigaciones.*',
            'aprobador.name as name_aprobador',
            'aprobador.lastname as lastname_aprobador',
            'analista.name as name_analista',
            'analista.lastname as lastname_analista',
            'states.name as fraude',
        )
            ->leftJoin('investigacion_fraude', 'investigacion_fraude.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('states', 'investigacion_fraude.fraude', '=', 'states.id')
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('users as analista', 'analista.id', '=', 'investigaciones.analista')
            ->leftJoin('users as aprobador', 'aprobador.id', '=', 'investigaciones.aprobador')
            ->distinct('investigacion_fraude.idInvestigacion')
            ->with(['estados', 'tipoInvestigaciones', 'tipoRiesgos', 'detalleRiesgos', 'tipoTramites', 'tipoSolicitudes', 'tipoSolicitantes', 'tipoPensiones', 'tipoDocumentos', 'prioridades']);

        if (Auth::user()->centroCosto != 1) {
            $investigaciones->where('investigaciones.centroCosto', $centro);
            $investigaciones->whereIn('investigaciones.estado', [16, 17, 18, 19, 20, 21, 7, 8, 9]);
        }

        if (isset($_GET['cat'])) {
            switch ($_GET['cat']) {
                case 'convivencia':
                    $investigaciones->where('TipoInvestigacion', 'CV');
                    $title = 'Investigaciones de convivencia';
                    break;
                case 'dependencia':
                    $investigaciones->where('TipoInvestigacion', 'DE');
                    $title = 'Investigaciones de dependencia';
                    break;
                case 'validacion':
                    $title = 'Investigaciones de validación';
                    $investigaciones->where('TipoInvestigacion', 'VD');
                    break;
                case 'escolaridad':
                    $title = 'Investigaciones de escolaridad';
                    $investigaciones->where('TipoInvestigacion', 'ES');
                    break;
                default:
                    break;
            }
        }

        $investigaciones = $investigaciones->get();
        return view('investigaciones.indexColpensionesTabs', compact('investigaciones', 'title'));
    } */

    //funcion para la pagina de investigacionesTodas donde se filtra por estados
    public function investigacionesTodas($data = null)
    {
        $title = 'Historial de todas las investigaciones';

        $estados = States::all();
        $estado = '';
        $cantidad = 0;

        $fechaLimite = false;
        $fechaFinalizacion = false;
        $fechaCancelacion = false;
        $btnInformePdf = false;
        $creador = false;
        $aprobador = false;
        $fechaAprobacion = false;
        $coordinador = false;
        $investigador = false;
        $btneditar = true;
        $btnver = true;
        $fechaObjecion = false;
        $btnrevision = false;
        $btninforme = true;
        $btninformeObjetado = true;

        $investigaciones = Investigaciones::select(
            'investigaciones.*',
            'investigador.name as name_investigador',
            'investigador.lastname as lastname_investigador',
            'coordinador.name as name_coordinador',
            'coordinador.lastname as lastname_coordinador',
            'analistaColpensiones.name as name_analistaColpensiones',
            'analistaColpensiones.lastname as lastname_analistaColpensiones',
            'aprobadorColpensiones.name as name_aprobadorColpensiones',
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones',
            'states.name as fraudeEstado',
            'investigacion_acreditacion.acreditacion',
            'investigacion_acreditacion.idBeneficiario'
        )
            ->leftJoin('investigacion_fraude', 'investigacion_fraude.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('states', 'investigacion_fraude.fraude', '=', 'states.id')
            ->leftJoin('investigacion_acreditacion', 'investigacion_acreditacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
            ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
            ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
            ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
            ->groupBy('investigaciones.id')
            ->orderBy('investigaciones.id', 'desc');

        if ($data != null) {
            $investigaciones = $investigaciones->where('investigaciones.estado', $data);
            $estado = States::where('id', $data)->select('name')->first();
        }

        switch ($data) {
            case '17':
                $fechaLimite = false;
                $fechaAprobacion = false;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = false;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '18':
                $fechaLimite = false;
                $fechaAprobacion = false;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = false;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '19':
                $fechaLimite = false;
                $fechaAprobacion = false;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = false;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '20':
                $fechaLimite = false;
                $fechaAprobacion = false;
                $fechaFinalizacion = false;
                $fechaCancelacion = true;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '21':
                $fechaLimite = false;
                $fechaAprobacion = false;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = true;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '3':
                $fechaLimite = true;
                $fechaAprobacion = true;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '5':
                $fechaLimite = true;
                $fechaAprobacion = true;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '6':
                $fechaLimite = true;
                $fechaAprobacion = false;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '7':
                $fechaLimite = true;
                $fechaAprobacion = true;
                $fechaFinalizacion = true;
                $fechaCancelacion = false;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = true;
                $btninformeObjetado = false;
                break;
            case '8':
                $fechaLimite = true;
                $fechaAprobacion = true;
                $fechaFinalizacion = true;
                $fechaCancelacion = false;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = true;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '9':
                $fechaLimite = false;
                $fechaAprobacion = false;
                $fechaFinalizacion = false;
                $fechaCancelacion = true;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = false;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '11':
                $fechaLimite = true;
                $fechaAprobacion = true;
                $fechaFinalizacion = false;
                $fechaCancelacion = false;
                $btneditar = true;
                $btnver = true;
                $fechaObjecion = true;
                $btnrevision = false;
                $btninforme = false;
                $btninformeObjetado = false;
                break;
            case '16':
                $fechaLimite = true;
                $fechaAprobacion = true;
                $fechaFinalizacion = true;
                $fechaObjecion = true;
                $fechaCancelacion = false;
                $btneditar = false;
                $btnver = true;
                $fechaObjecion = true;
                $btnrevision = false;
                $btninforme = true;
                $btninformeObjetado = true;
                break;
        }

        if (isset($_GET['cat'])) {
            if ($_GET['cat'] != null) {
                $investigaciones = $investigaciones->where('investigaciones.estado', $_GET['cat']);
            }
        }

        $cantidad = $investigaciones->distinct('investigacion_fraude.idInvestigacion')->count();
        $investigaciones = $investigaciones->paginate(25);


        return view('investigaciones.listado', compact('investigaciones', 'title', 'estados', 'estado', 'cantidad', 'fechaAprobacion', 'fechaLimite', 'fechaFinalizacion', 'fechaCancelacion', 'fechaObjecion', 'btnver', 'btnrevision', 'btninforme', 'btneditar', 'btninformeObjetado'));
    }

    public function misInvestigaciones($estadoFiltro = null)
    {
        $fechaLimite = true;
        $fechaAprobacion = true;
        $fechaFinalizacion = true;
        $fechaCancelacion = true;
        $btneditar = true;
        $btnver = true;
        $fechaObjecion = true;
        $btnrevision = true;
        $btninforme = true;
        $btninformeObjetado = true;
        $creador = true;
        $aprobador = true;
        $coordinador = true;
        $investigador = true;

        $title = 'Mis investigaciones';

        // $data = InvestigacionAsignacion::query();

        //switch (Auth::user()->roles->pluck('id')[0]) {
           // case 5: //investigador
               // $data = $data->where('investigacion_asignacion.Investigador', Auth::user()->id);
               // $filtroEstado = [5, 11];
               // break;
            //case 6: //Coordinador regional
               // $data = $data->where('investigacion_asignacion.CoordinadorRegional', Auth::user()->id);
              //  $filtroEstado = [3, 5, 6, 7, 23, 25, 11];
              //  break;
            //case 11: //Analista
              //  $data = $data->where('investigacion_asignacion.Analista', Auth::user()->id);
              // $filtroEstado = [6];
              //  break;
           // case 9: //Aprobador Colpensiones
              //  $filtroEstado = [17, 19, 20, 21, 7, 8, 16];
            //   break;
           // case 12: //Creador/analista
              //  $filtroEstado = [17, 19, 7, 8];
            //    break;
        //}
        // $data = $data->pluck('idInvestigacion')->toArray();

        // Si $data está vacío, inicializa como un array vacío para evitar errores en whereIn
        // if (empty($data)) {
        //    $data = [];
        // }

        $investigaciones = Investigaciones::select(
    'investigaciones.*',
    'coordinador.name as name_coordinador',
    'coordinador.lastname as lastname_coordinador',
    'investigador.name as name_investigador',
    'investigador.lastname as lastname_investigador',
    'analista.name as name_analista',
    'analista.lastname as lastname_analista',
    'analistaColpensiones.name as name_analistaColpensiones',
    'analistaColpensiones.lastname as lastname_analistaColpensiones',
    'aprobadorColpensiones.name as name_aprobadorColpensiones',
    'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones'
);

// APLICAR FILTRO DE ROL DIRECTAMENTE EN EL JOIN (Esto reemplaza el switch que comentaste en el paso 1)
switch (Auth::user()->roles->pluck('id')[0]) {
    case 5: //investigador
        $investigaciones = $investigaciones->join('investigacion_asignacion', function ($join) {
            $join->on('investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
                 ->where('investigacion_asignacion.Investigador', Auth::user()->id);
        });
        break;
    case 6: //Coordinador regional
        $investigaciones = $investigaciones->join('investigacion_asignacion', function ($join) {
            $join->on('investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
                 ->where('investigacion_asignacion.CoordinadorRegional', Auth::user()->id);
        });
        break;
    case 11: //Analista
        $investigaciones = $investigaciones->join('investigacion_asignacion', function ($join) {
            $join->on('investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
                 ->where('investigacion_asignacion.Analista', Auth::user()->id);
        });
        break;
    default:
        // Para otros roles que NO FILTRAN por asignación (Aprobador, Creador), usamos un LEFT JOIN.
        $investigaciones = $investigaciones->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id');
        break;
}

// RESTO DE LOS JOINs Y ORDER BY (ESTO CONTINÚA EXACTAMENTE IGUAL)
$investigaciones = $investigaciones->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
    ->leftJoin('users as analista', 'analista.id', '=', 'investigacion_asignacion.Analista')
    ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
    ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
    ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
    ->orderBy('investigaciones.id', 'desc');
            //->whereIn('investigaciones.id', $data);

        switch (Auth::user()->roles->pluck('id')[0]) {
            case 9: //Aprobador Colpensiones
                $investigaciones = $investigaciones->where('investigaciones.aprobador', Auth::user()->id);
                break;
            case 12: //Creador/analista
                $investigaciones = $investigaciones->where('investigaciones.analista', Auth::user()->id);
                break;
        }

        if ($estadoFiltro !== null) {
            $investigaciones = $investigaciones->where('investigaciones.estado', $estadoFiltro);
            $estadoslista = States::find($estadoFiltro);
            $title = 'Mis investigaciones en estado ' . $estadoslista->name;
        }
        $investigaciones = $investigaciones->paginate(10);
        if (Auth::user()->centroCosto == 1) {
            return view('investigaciones.misinvestigaciones', compact('investigaciones', 'title', 'fechaLimite', 'fechaAprobacion', 'fechaFinalizacion', 'fechaCancelacion', 'btneditar', 'btnver', 'fechaObjecion', 'btnrevision', 'btninforme', 'btninformeObjetado', 'creador', 'aprobador', 'coordinador', 'investigador'));
        } else {
            switch ($estadoFiltro) {
                case 17:
                    $fechaLimite = true;
                    $fechaAprobacion = true;
                    $fechaFinalizacion = true;
                    $fechaCancelacion = true;
                    $btneditar = true;
                    $btnver = false;
                    $fechaObjecion = false;
                    $btnrevision = true;
                    $btninforme = false;
                    $btninformeObjetado = false;
                    $creador = false;
                    $aprobador = false;
                    $coordinador = true;
                    $investigador = true;
                    break;
                case 19:
                    $fechaLimite = false;
                    $fechaAprobacion = false;
                    $fechaFinalizacion = false;
                    $fechaCancelacion = false;
                    $btneditar = false;
                    $btnver = false;
                    $fechaObjecion = false;
                    $btnrevision = true;
                    $btninforme = false;
                    $btninformeObjetado = false;
                    $creador = false;
                    $aprobador = false;
                    $coordinador = true;
                    $investigador = true;
                    break;
                case 7:
                    $fechaLimite = false;
                    $fechaAprobacion = false;
                    $fechaFinalizacion = false;
                    $fechaCancelacion = false;
                    $btneditar = false;
                    $btnver = true;
                    $fechaObjecion = false;
                    $btnrevision = false;
                    $btninforme = true;
                    $btninformeObjetado = false;
                    $creador = false;
                    $aprobador = false;
                    $coordinador = true;
                    $investigador = true;
                    break;
                case 8:
                case 9:
                case 6:
                case 3:
                case 5:
                case 11:
                case 21:
                    $fechaLimite = false;
                    $fechaAprobacion = false;
                    $fechaFinalizacion = false;
                    $fechaCancelacion = false;
                    $btneditar = false;
                    $btnver = true;
                    $fechaObjecion = false;
                    $btnrevision = false;
                    $btninforme = false;
                    $btninformeObjetado = false;
                    $creador = false;
                    $aprobador = false;
                    $coordinador = true;
                    $investigador = true;
                    break;
                case 16:
                    $fechaLimite = false;
                    $fechaAprobacion = false;
                    $fechaFinalizacion = false;
                    $fechaCancelacion = false;
                    $btneditar = false;
                    $btnver = true;
                    $fechaObjecion = true;
                    $btnrevision = false;
                    $btninforme = false;
                    $btninformeObjetado = true;
                    $creador = false;
                    $aprobador = false;
                    $coordinador = true;
                    $investigador = true;
                    break;
            }

            return view('investigaciones.misinvestigacionesColpensiones', compact('investigaciones', 'title', 'fechaLimite', 'fechaAprobacion', 'fechaFinalizacion', 'fechaCancelacion', 'btneditar', 'btnver', 'fechaObjecion', 'btnrevision', 'btninforme', 'btninformeObjetado', 'creador', 'aprobador', 'coordinador', 'investigador'));
        }
    }

    public function migrupo($estadoFiltro = null)
    {
        $fechaLimite = true;
        $fechaAprobacion = true;
        $fechaFinalizacion = true;
        $fechaCancelacion = true;
        $btneditar = true;
        $btnver = true;
        $fechaObjecion = true;
        $btnrevision = true;
        $btninforme = true;
        $btninformeObjetado = true;
        $creador = true;
        $aprobador = true;
        $coordinador = true;
        $investigador = true;
        $title = 'Trazabilidad mi grupo';
        $data = User::where('coordinador', Auth::user()->id)->pluck('id')->toArray();
        if (empty($data)) {
            $data = [];
        }

        $investigaciones = Investigaciones::select(
            'investigaciones.*',
            'coordinador.name as name_coordinador',
            'coordinador.lastname as lastname_coordinador',
            'investigador.name as name_investigador',
            'investigador.lastname as lastname_investigador',
            'analista.name as name_analista',
            'analista.lastname as lastname_analista',
            'analistaColpensiones.name as name_analistaColpensiones',
            'analistaColpensiones.lastname as lastname_analistaColpensiones',
            'aprobadorColpensiones.name as name_aprobadorColpensiones',
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones'
        )
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
            ->leftJoin('users as analista', 'analista.id', '=', 'investigacion_asignacion.Analista')
            ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
            ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
            ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
            ->where(function ($query) use ($data) {
                $query->orWhereIn('investigaciones.analista', $data)
                    ->orWhereIn('investigaciones.aprobador', $data);
            })
            ->orderBy('investigaciones.id', 'desc')
            ->where('investigaciones.estado', $estadoFiltro);

        // Condición adicional basada en el estado
        if ($estadoFiltro !== null) {
            $estadoslista = States::find($estadoFiltro);
            $title = 'Trazabilidad mi grupo - investigaciones en estado ' . $estadoslista->name;
        }

        // Paginación de los resultados
        $investigaciones = $investigaciones->paginate(10);


        return view('investigaciones.migrupo', compact('investigaciones', 'title', 'fechaLimite', 'fechaAprobacion', 'fechaFinalizacion', 'fechaCancelacion', 'btneditar', 'btnver', 'fechaObjecion', 'btnrevision', 'btninforme', 'btninformeObjetado', 'creador', 'aprobador', 'coordinador', 'investigador'));
    }

    public function miCentroCosto(Request $request, $estadoFiltro = null)
    {
        $fechaLimite = true;
        $fechaAprobacion = true;
        $fechaFinalizacion = true;
        $fechaCancelacion = true;
        $btneditar = true;
        $btnver = true;
        $fechaObjecion = true;
        $btnrevision = true;
        $btninforme = true;
        $btninformeObjetado = true;
        $creador = true;
        $aprobador = true;
        $coordinador = true;
        $investigador = true;
        $title = 'Centro de costos';

        $investigaciones = Investigaciones::select(
            'investigaciones.*',
            'analistaColpensiones.name as name_analistaColpensiones',
            'analistaColpensiones.lastname as lastname_analistaColpensiones',
            'aprobadorColpensiones.name as name_aprobadorColpensiones',
            'aprobadorColpensiones.lastname as lastname_aprobadorColpensiones'
        )
        ->leftJoin('users as analistaColpensiones', 'analistaColpensiones.id', '=', 'investigaciones.analista')
        ->leftJoin('users as aprobadorColpensiones', 'aprobadorColpensiones.id', '=', 'investigaciones.aprobador')
        ->leftJoin('colpensiones.investigaciones_beneficiarios', 'colpensiones.investigaciones_beneficiarios.IdInvestigacion', '=', 'investigaciones.id')
        ->orderBy('investigaciones.id', 'desc')
        ->where('investigaciones.CentroCosto', Auth::user()->centroCostos->codigo);


        // Condición adicional basada en el estado
        if ($estadoFiltro !== null) {
            $investigaciones = $investigaciones->where('investigaciones.estado', $estadoFiltro);
            $estadoslista = States::find($estadoFiltro);
            $title = 'Centro de costos - investigaciones en estado ' . $estadoslista->name;
        }

        $filtro = $request->input('filtro');
        // Aplicar el filtro de búsqueda si está presente
        if ($filtro) {
            $investigaciones = $investigaciones->where(function($query) use ($filtro) {
                $query->where('investigaciones.NumeroRadicacionCaso', 'like', "%{$filtro}%")
                ->orWhere('investigaciones.PrimerNombre', 'like', "%{$filtro}%")
              ->orWhere('investigaciones.SegundoNombre', 'like', "%{$filtro}%")
              ->orWhere('investigaciones.PrimerApellido', 'like', "%{$filtro}%")
              ->orWhere('investigaciones.SegundoApellido', 'like', "%{$filtro}%")
              ->orWhere('investigaciones.Solicitud', 'like', "%{$filtro}%")
              ->orWhere('investigaciones.Observacion', 'like', "%{$filtro}%")
              ->orWhere('investigaciones.NumeroDeDocumento', 'like', "%{$filtro}%")
              ->orWhere('investigaciones.IdCase', 'like', "%{$filtro}%")
                ->orWhere('colpensiones.investigaciones_beneficiarios.NumeroDocumento', 'like',  "%{$filtro}%");
            });
        }

        // Paginación de los resultados
        $investigaciones = $investigaciones->paginate(10);
        return view('investigaciones.miCentroCostoColpensiones', compact('investigaciones', 'title', 'fechaLimite', 'fechaAprobacion', 'fechaFinalizacion', 'fechaCancelacion', 'btneditar', 'btnver', 'fechaObjecion', 'btnrevision', 'btninforme', 'btninformeObjetado', 'creador', 'aprobador', 'coordinador', 'investigador'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $TipoInvestigacion = TipoInvestigacion::all();
        $TipoRiesgo = TipoRiesgo::where('codigo','like',"RC%")->get();
        $TipoPension = TipoPension::all();
        $TipoTramite = TipoTramite::all();
        $DetalleRiesgo = DetalleRiesgo::all();
        $TipoSolicitud = TipoSolicitud::all();
        $TipoSolicitante = TipoSolicitante::all();
        $TipoDocumento = TipoDocumento::all();
        $TipoPrioridad = TipoPrioridad::all();
        $TipoJuntas = Juntas::select('*')->orderBy('nombre', 'asc')->get();
        return view('investigaciones.create', compact('TipoJuntas', 'TipoInvestigacion', 'TipoRiesgo', 'TipoPension', 'TipoTramite', 'DetalleRiesgo', 'TipoSolicitud', 'TipoSolicitante', 'TipoDocumento', 'TipoPrioridad'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Iniciar una transacción
        DB::beginTransaction();
        try {
            $investigacion = Investigaciones::find($id);
            
            $facturaAuxilios = [
                ['id' => '1',    'name' => 'Factura de Gastos Funerarios'],
                ['id' => '2',    'name' => 'Contrato Preexequial'],
                ['id' => '3',    'name' => 'Contrato Prenecesidad'],
                ['id' => '4',    'name' => 'Póliza de Seguro'],
                ['id' => '5',    'name' => 'Recibo de Caja'],
                ['id' => '0',    'name' => 'No aplica'],
            ];

            $actividadestipoinvestigacion = actividadestipoinvestigacion::where('TipoInvestigacionCode', trim($investigacion->TipoInvestigacion))->pluck('ActividadName');
            $TipoInvestigacion = TipoInvestigacion::all();
            $causalObjecion = TipoObjecion::all();
            $InvestigacionRegiones = InvestigacionRegion::all();
            $campoSino = States::whereIn('id', [12, 13])->get();
            $seleccionAcreditacion = States::whereIn('id', [14, 15])->get();
            $TipoRiesgo = TipoRiesgo::where('codigo','like',"RC%")->get();
            $TipoPension = TipoPension::all();
            $TipoTramite = TipoTramite::all();
            $DetalleRiesgo = DetalleRiesgo::all();
            $TipoSolicitud = TipoSolicitud::all();
            $TipoSolicitante = TipoSolicitante::all();
            $TipoDocumento = TipoDocumento::all();
            $historialEstados = investigacionesObservacionesEstado::select(
                'investigaciones_observaciones_estados.*',
                'roles.name as rol_usuario'
            )
                ->join('users', 'investigaciones_observaciones_estados.idUsuario', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('investigaciones_observaciones_estados.idInvestigacion', $id)
                ->orderBy('id', 'asc')
                ->get();
            $trazabilidadActividades = TrazabilidadActividadesRealizadas::select(
                'investigacion_trazabilidad_actividades_realizadas.*',
                'roles.name as rol_usuario'
            )
                ->join('users', 'investigacion_trazabilidad_actividades_realizadas.idUsuario', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('investigacion_trazabilidad_actividades_realizadas.idInvestigacion', $id)
                ->orderBy('fecha', 'asc')
                ->get();

            $secciones = SeccionesFormulario::select('secciones.nombre')
                ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
                ->where('investigacion', $investigacion->TipoInvestigacion)
                ->get();

            $estados = [];
            $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
            $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->count();
            if ($entrevistaSolicitante == 0) {
                InvestigacionEntrevistaSolicitante::create(['idInvestigacion' => $id]);
            }
            $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
            $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->count();
            if ($esFraude == 0) {
                InvestigacionFraude::create(['idInvestigacion' => $id, 'fraude' => 13]);
            }
            $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
            foreach ($beneficiarios as $beneficiario) {
                $investigacionVerificacion = InvestigacionVerificacion::where('idBeneficiario', $beneficiario->id)->count();
                if ($investigacionVerificacion === 0) {
                    InvestigacionVerificacion::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $acreditacion = InvestigacionAcreditacion::where('idBeneficiario', $beneficiario->id)->count();
                if ($acreditacion === 0) {
                    InvestigacionAcreditacion::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $acreditaciones = InvestigacionAcreditacion::select('investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->get();
            $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->count();
            if ($entrevistaFamiliares == 0) {
                InvestigacionEntrevistaFamiliares::create(['idInvestigacion' => $id]);
            }
            $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();

            $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->count();
            if ($gastosVivienda == 0) {
                InvestigacionGastosVivienda::create(['idInvestigacion' => $id]);
            }
            $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();

            $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->count();
            if ($auxilioFunerario == 0) {
                InvestigacionAuxilioFunerario::create(['idInvestigacion' => $id]);
            }
            $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();

            $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->count();
            if ($laborCampo == 0) {
                InvestigacionLaborCampo::create(['idInvestigacion' => $id]);
            }
            $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
			if ($investigacion->estado == 7 && (Auth::user()->roles[0]->id == 7 || Auth::user()->roles[0]->id == 9 || Auth::user()->roles[0]->id == 12 ||  Auth::user()->roles[0]->id == 1)) { //finalizado
                $estados = States::whereIn('id', [7, 8])->get(); //finalizado, objetado
            } elseif ($investigacion->estado == 8 && (Auth::user()->roles[0]->id == 7 || Auth::user()->roles[0]->id == 9 ||  Auth::user()->roles[0]->id == 1)) { //objetado
                $estados = States::whereIn('id', [8, 21, 22])->get(); //objetado, objetado
            } elseif ($investigacion->estado == 16 && (Auth::user()->roles[0]->id == 7 || Auth::user()->roles[0]->id == 9 || Auth::user()->roles[0]->id == 12 ||  Auth::user()->roles[0]->id == 1)) { //objetado
                $estados = States::whereIn('id', [16, 8])->get(); //objetado, objetado
            }

            /*
            if ($investigacion->estado == 7 && (Auth::user()->roles[0]->id == 7 || Auth::user()->roles[0]->id == 9 || Auth::user()->roles[0]->id == 12 ||  Auth::user()->roles[0]->id == 1)) { //finalizado
                $estados = States::whereIn('id', [7, 8])->get(); //finalizado, objetado
            } elseif ($investigacion->estado == 8 && (Auth::user()->roles[0]->id == 7 || Auth::user()->roles[0]->id == 9 ||  Auth::user()->roles[0]->id == 1)) { //objetado
                $estados = States::whereIn('id', [8, 21, 22])->get(); //objetado, objetado
            } elseif ($investigacion->estado == 16 && (Auth::user()->roles[0]->id == 7 || Auth::user()->roles[0]->id == 9 || Auth::user()->roles[0]->id == 12 ||  Auth::user()->roles[0]->id == 1)) { //objetado
                $estados = States::whereIn('id', [16, 8])->get(); //objetado, objetado
            }*/

            $estadoCount = false;
            $centrocostos_investigacion = $investigacion->CentroCosto;
            $centrocostos_usuario = Auth::user()->centroCostos->codigo;

            
            if($centrocostos_investigacion == $centrocostos_usuario || $centrocostos_usuario == 'JA'){
                
                if (count($estados) > 0) {
                    $estadoCount = true;
                } else {
                    $estadoCount = false;
                }
            }

            

            $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->count();
            if ($validacionDocumentalCausante == 0) {
                InvestigacionesValidacionDocumentalCausante::create(['idInvestigacion' => $id]);
            }
            $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
            $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->count();
            if ($estudioAuxiliar == 0) {
                InvestigacionEstudiosAuxiliares::create(['idInvestigacion' => $id]);
            }
            $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
            // Crear registros de validación documental para beneficiarios si no existen
            foreach ($beneficiarios as $beneficiario) {
                $validacionDocumentalBeneficiario = InvestigacionValidacionDocumentalBeneficiarios::where('idBeneficiario', $beneficiario->id)->count();
                if ($validacionDocumentalBeneficiario == 0) {
                    InvestigacionValidacionDocumentalBeneficiarios::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $escolaridadBeneficiario = InvestigacionEscolaridad::where('idBeneficiario', $beneficiario->id)->count();
                if ($escolaridadBeneficiario == 0) {
                    InvestigacionEscolaridad::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
            $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->count();
            if ($AntecedentesCausante == 0) {
                InvestigacionConsultasAntecedentesCausante::create(['idInvestigacion' => $id]);
            }
            $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
            $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $antecedentesBeneficiario = InvestigacionConsultasAntecedentesBeneficiarios::where('idBeneficiario', $beneficiario->id)->count();
                if ($antecedentesBeneficiario  == 0) {
                    InvestigacionConsultasAntecedentesBeneficiarios::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
            $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
            $documentos = Storage::disk('investigaciones')->allFiles('radicado/' . $investigacion->nombreCarpeta);
            $coordinador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->CoordinadorRegional)->first();
            $investigador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Investigador)->first();
            $auxiliar = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Auxiliar)->first();
            $analista = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Analista)->first();
            $creador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $investigacion->analista)->first();
            $aprobador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $investigacion->aprobador)->first();
            
            // Obtener coordinadores, investigadores y auxiliares
            // Commit de la transacción si todas las operaciones tienen éxito
            DB::commit();
            // Renderizar la vista con los datos obtenidos
            return view('investigaciones.show', compact('causalObjecion', 'actividadestipoinvestigacion','creador', 'aprobador', 'investigacion', 'TipoInvestigacion', 'TipoRiesgo', 'TipoPension', 'TipoTramite', 'DetalleRiesgo', 'TipoSolicitud', 'TipoSolicitante', 'TipoDocumento', 'asignacion', 'beneficiarios', 'documentos', 'estados', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'campoSino', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'seleccionAcreditacion', 'secciones', 'estadoCount', 'InvestigacionRegiones'));
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción
            echo $e;
            DB::rollBack();
            // Manejar el error según sea necesario
            //return redirect()->back()->with('error', 'Error al editar la investigación: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        DB::beginTransaction();
        try {
            $investigacion = Investigaciones::find($id);
            $novedades = Novedad::select(
                'novedades.*',
                'roles.name as rol_usuario'
            )
                ->join('users', 'novedades.idUsuario', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('novedades.idInvestigacion', $id)
                ->orderBy('fecha', 'asc')
                ->get();
            //$RadicadoAsociado = Investigaciones::where('NumeroRadicacionCaso',$investigacion->RadicadoAsociado)->first();
            Storage::disk('investigaciones')->makeDirectory('/radicado/' . $investigacion->nombreCarpeta . '/investigacion/');
            
            $facturaAuxilios = [
                ['id' => '1',    'name' => 'Factura de Gastos Funerarios'],
                ['id' => '2',    'name' => 'Contrato Preexequial'],
                ['id' => '3',    'name' => 'Contrato Prenecesidad'],
                ['id' => '4',    'name' => 'Póliza de Seguro'],
                ['id' => '5',    'name' => 'Recibo de Caja'],
                ['id' => '0',    'name' => 'No aplica'],
            ];
            $actividadestipoinvestigacion = actividadestipoinvestigacion::where('TipoInvestigacionCode', trim($investigacion->TipoInvestigacion))->pluck('ActividadName');
            $departamentos  = Departamento::all();
            $municipio  = Municipio::all();
            $TipoInvestigacion = TipoInvestigacion::all();
            $InvestigacionRegiones = InvestigacionRegion::all();
            $InvestigacionPrioridad = TipoPrioridad::all();
            $campoSino = States::whereIn('id', [12, 13])->get();
            $seleccionAcreditacion = States::whereIn('id', [14, 15])->get();
            $TipoRiesgo = TipoRiesgo::where('codigo','like',"RC%")->get();
            $TipoPension = TipoPension::all();
            $TipoTramite = TipoTramite::all();
            $DetalleRiesgo = DetalleRiesgo::all();
            $TipoSolicitud = TipoSolicitud::all();
            $TipoSolicitante = TipoSolicitante::all();
            $TipoDocumento = TipoDocumento::all();
            $historialEstados = investigacionesObservacionesEstado::select(
                'investigaciones_observaciones_estados.*',
                'roles.name as rol_usuario'
            )
                ->join('users', 'investigaciones_observaciones_estados.idUsuario', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('investigaciones_observaciones_estados.idInvestigacion', $id)
                ->orderBy('id', 'asc')
                ->get();
            $trazabilidadActividades = TrazabilidadActividadesRealizadas::select(
                'investigacion_trazabilidad_actividades_realizadas.*',
                'roles.name as rol_usuario'
            )
                ->join('users', 'investigacion_trazabilidad_actividades_realizadas.idUsuario', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('investigacion_trazabilidad_actividades_realizadas.idInvestigacion', $id)
                ->orderBy('fecha', 'asc')
                ->get();


            $secciones = SeccionesFormulario::select('secciones.nombre')
                ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
                ->where('investigacion', $investigacion->TipoInvestigacion)
                ->get();
            $estados = [];
            $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
            $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->count();
            if ($entrevistaSolicitante == 0) {
                InvestigacionEntrevistaSolicitante::create(['idInvestigacion' => $id]);
            }
            $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
            $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->count();
            if ($esFraude == 0) {
                InvestigacionFraude::create(['idInvestigacion' => $id, 'fraude' => 13]);
            }
            $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
            foreach ($beneficiarios as $beneficiario) {
                $investigacionVerificacion = InvestigacionVerificacion::where('idBeneficiario', $beneficiario->id)->count();
                if ($investigacionVerificacion === 0) {
                    InvestigacionVerificacion::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $acreditacion = InvestigacionAcreditacion::where('idBeneficiario', $beneficiario->id)->count();
                if ($acreditacion === 0) {
                    InvestigacionAcreditacion::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $acreditaciones = InvestigacionAcreditacion::select('investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->get();
            $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->count();
            if ($entrevistaFamiliares == 0) {
                InvestigacionEntrevistaFamiliares::create(['idInvestigacion' => $id]);
            }
            $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();

            $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->count();
            if ($gastosVivienda == 0) {
                InvestigacionGastosVivienda::create(['idInvestigacion' => $id]);
            }
            $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();

            $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->count();
            if ($auxilioFunerario == 0) {
                InvestigacionAuxilioFunerario::create(['idInvestigacion' => $id]);
            }
            $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();

            $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->count();
            if ($laborCampo == 0) {
                InvestigacionLaborCampo::create(['idInvestigacion' => $id]);
            }
            $causalObjecion = TipoObjecion::all();
            $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
            if ((Auth::user()->roles[0]->id == 6 ||Auth::user()->roles[0]->id == 6 || Auth::user()->roles[0]->id == 2  || Auth::user()->roles[0]->id == 1) && $investigacion->estado == 3) { //solicitado
                $estados = States::whereIn('id', [3, 5])->get(); //solicitado, asignado
            } elseif ((Auth::user()->roles[0]->id == 3 )  && $investigacion->estado == 7) { //Coordinador Operativo / finalizado
                $estados = States::whereIn('id', [7])->get(); //Finalizado
            } elseif ((Auth::user()->roles[0]->id == 3 )  && $investigacion->estado == 16) { //Coordinador Operativo / finalizado Objetado
                $estados = States::whereIn('id', [16])->get(); //Finalizado Objetado
            } elseif ((Auth::user()->roles[0]->id == 9 )  && $investigacion->estado == 16) { //Coordinador Operativo / finalizado Objetado
                $estados = States::whereIn('id', [17,19])->get(); //Finalizado Objetado
            } elseif ((Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 6  || Auth::user()->roles[0]->id == 4 || Auth::user()->roles[0]->id == 5 || Auth::user()->roles[0]->id == 2 || Auth::user()->roles[0]->id == 1 || Auth::user()->roles[0]->id == 11)  && $investigacion->estado == 5) { //asignado
                $estados = States::whereIn('id', [5, 6])->get(); //asignado, revision
            } elseif ((Auth::user()->roles[0]->id == 11 || Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 6 || Auth::user()->roles[0]->id == 2  || Auth::user()->roles[0]->id == 1) && $investigacion->estado == 6) { //en revision
                if ($investigacion->esObjetado == 0) {
                    $estados = States::whereIn('id', [6, 7, 11])->get(); //revision, finalizado, correccion, cancelado
                } else {
                    $estados = States::whereIn('id', [6, 16, 11])->get(); //revision, finalizado, correccion, cancelado
                }
            } elseif ((Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 6 || Auth::user()->roles[0]->id == 5  || Auth::user()->roles[0]->id == 4 || Auth::user()->roles[0]->id == 2  || Auth::user()->roles[0]->id == 1 || Auth::user()->roles[0]->id == 11)  && $investigacion->estado == 11) { //en correccion
                $estados = States::whereIn('id', [11, 6])->get(); //correcion, revision
            } elseif ((Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 2 || Auth::user()->roles[0]->id == 5  || Auth::user()->roles[0]->id == 4 || Auth::user()->roles[0]->id == 6  || Auth::user()->roles[0]->id == 1)  && $investigacion->estado == 21) { //Aprobado Objetado
                $estados = States::whereIn('id', [21, 23, 24, 25])->get(); //Aceptacion, no aceptacion, no aceptacion correccion
            }
            if (Auth::user()->roles[0]->id == 2 || Auth::user()->roles[0]->id == 3) {
                $estados = States::whereIn('id', [3, 5, 6, 7, 9, 11, 23, 24, 25])->get();
            }

            $estadoCount = false;
            $centrocostos_investigacion = $investigacion->CentroCosto;
            $centrocostos_usuario = Auth::user()->centroCostos->codigo;

            
            if($centrocostos_investigacion == $centrocostos_usuario || $centrocostos_usuario == 'JA'){
                
                if (count($estados) > 0) {
                    $estadoCount = true;
                } else {
                    $estadoCount = false;
                }
            }

			if( Auth::user()->roles[0]->id == 7 && $investigacion->estado == 17){
                $estados = States::whereIn('id', [ 18, 19])->get();
                $estadoCount = true;
            }elseif(Auth::user()->roles[0]->id == 7 && $investigacion->estado == 17  ){
                $estados = States::whereIn('id', [8, 17, 18, 19, 20, 21])->get();
                $estadoCount = true;
            }			
			
            $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->count();
            if ($validacionDocumentalCausante == 0) {
                InvestigacionesValidacionDocumentalCausante::create(['idInvestigacion' => $id]);
            }
            $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
            $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->count();
            if ($estudioAuxiliar == 0) {
                InvestigacionEstudiosAuxiliares::create(['idInvestigacion' => $id]);
            }
            $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
            // Crear registros de validación documental para beneficiarios si no existen
            foreach ($beneficiarios as $beneficiario) {
                $validacionDocumentalBeneficiario = InvestigacionValidacionDocumentalBeneficiarios::where('idBeneficiario', $beneficiario->id)->count();
                if ($validacionDocumentalBeneficiario == 0) {
                    InvestigacionValidacionDocumentalBeneficiarios::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $escolaridadBeneficiario = InvestigacionEscolaridad::where('idBeneficiario', $beneficiario->id)->count();
                if ($escolaridadBeneficiario == 0) {
                    InvestigacionEscolaridad::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
            $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->count();
            if ($AntecedentesCausante == 0) {
                InvestigacionConsultasAntecedentesCausante::create(['idInvestigacion' => $id]);
            }
            $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
            $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $antecedentesBeneficiario = InvestigacionConsultasAntecedentesBeneficiarios::where('idBeneficiario', $beneficiario->id)->count();
                if ($antecedentesBeneficiario == 0) {
                    InvestigacionConsultasAntecedentesBeneficiarios::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
            $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
            $documentos = Storage::disk('investigaciones')->allFiles('radicado/' . $investigacion->nombreCarpeta);
            $coordinador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->CoordinadorRegional)->first();
            $investigador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Investigador)->first();
            $auxiliar = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Auxiliar)->first();
            $analista = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Analista)->first();
            $creador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $investigacion->analista)->first();
            $aprobador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $investigacion->aprobador)->first();
            // Obtener coordinadores, investigadores y auxiliares
            $coordinadores = User::role('Coordinador regional')->selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('users.estado', 1)->orderBy('users.name', 'asc')->get();
            $investigadores = User::role('Investigador')->selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('users.estado', 1)->orderBy('users.name', 'asc')->get();
            $auxiliares = User::role('Auxiliar')->selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('users.estado', 1)->orderBy('users.name', 'asc')->get();
            $analistas = User::role('Analista')->selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('users.estado', 1)->orderBy('users.name', 'asc')->get();
            // Commit de la transacción si todas las operaciones tienen éxito
            DB::commit();
            // Renderizar la vista con los datos obtenidos
            return view('investigaciones.edit', compact('municipio', 'actividadestipoinvestigacion','departamentos', 'novedades', 'causalObjecion', 'InvestigacionPrioridad', 'creador', 'aprobador', 'investigacion', 'TipoInvestigacion', 'TipoRiesgo', 'TipoPension', 'TipoTramite', 'DetalleRiesgo', 'TipoSolicitud', 'TipoSolicitante', 'TipoDocumento', 'coordinadores', 'investigadores', 'auxiliares', 'analistas', 'asignacion', 'beneficiarios', 'documentos', 'estados', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'campoSino', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'seleccionAcreditacion', 'secciones', 'estadoCount', 'InvestigacionRegiones'));
        } catch (\Exception $e) {
            // En caso de error, revertir la transacción
            echo $e;
            DB::rollBack();
            // Manejar el error según sea necesario
            //return redirect()->back()->with('error', 'Error al editar la investigación: ' . $e->getMessage());
        }
    }

    public function investigacionstep(Request $request, $id)
    {
        $investigacion = Investigaciones::find($id);

        if ($request->estado == $investigacion->estado) {
            return redirect()->back()->withErrors(['error' => 'No se puede realizar una actualización de estado al mismo estado, por favor revise la información.']);
        }

        $investigacion->update(['estado' => $request->estado]);
        investigacionesObservacionesEstado::create([
            'idInvestigacion' => $id,
            'idUsuario' => Auth::user()->id,
            'idEstado' => $request->estado,
            'observacion' => $request->observacion,
            'CausalPrimariaObjecion' => $request->CausalPrimariaObjecion,
            'CausalSecundariaObjecion' => $request->CausalSecundariaObjecion,
        ]);
        if ($investigacion->cantidadObjeciones>0 && $request->estado ==7){
            $request->estado==16;
        }
		
       $notificacionTxt = 'Se ha cambiado de estado la investigación # ' . $investigacion->id . ', Radicado # ' . $investigacion->CasoPadreOriginal . '  a estado ';
		
        if ($request->estado == 7 ) {

            $actualizacion = Investigaciones::find($investigacion->id);
            $actualizacion->FechaFinalizacion = now()->timezone('America/Bogota');
            $actualizacion->update(['FechaFinalizacion' => $actualizacion->FechaFinalizacion]);
			$notificacionTxt = $notificacionTxt . 'FINALIZADA  por el usuario ' . Auth::user()->name . ' ' . Auth::user()->lastname ;		
			Notificaciones::create(['idUsuario' => $investigacion->analista, 'mensaje' => $notificacionTxt]);				
			if ($investigacion->analista !=   Auth::user()->id)
				Notificaciones::create(['idUsuario' => Auth::user()->id, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);					
            if ($this->creacionCarpetaFinalizada($id, $request->estado)) {
                switch ($request->estado) {
                    case '7':
                        $rutaDirectorio = 'investigaciones/finalizados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                        $continuar = true;
                        break;
                    case '16':
                        $rutaDirectorio = 'investigaciones/finalizadosObjetados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                        $continuar = true;
                        break;
                }
                

                if (!Storage::exists($rutaDirectorio)) {
                    Storage::makeDirectory($rutaDirectorio);
                }
                if ($this->pdfGenerator->generarInformeInvestigacionPDF($id, 7)) {
                    $this->pdfGenerator->generarInformeInvestigacionSoportesPDF($id);
                    return back()->with('info', 'Investigación finalizada correctamente.');
                }
            }
        }

       

        switch ($request->estado) {
            case 18: //Aprobado
                date_default_timezone_set('America/Bogota');
                $actualizacion = Investigaciones::find($investigacion->id);
                $prioridad = TipoPrioridad::find($actualizacion->Prioridad);
                $actualizacion->FechaAprobacion = now()->timezone('America/Bogota');
                $fechaSolicitud = now()->timezone('America/Bogota');
                $tipo = TipoInvestigacion::where('codigo', $investigacion->TipoInvestigacion)->first();
                $tiempoEntrega = $tipo->TiempoEntrega;
                if ($prioridad->TiempoEntrega != null) {
                    $tiempoEntrega = $prioridad->TiempoEntrega;
                }
                $fechaEntrega = $fechaSolicitud->copy()->addDays($tiempoEntrega);
                $fechaLimite = $this->esFestivo(Carbon::createFromFormat('Y-m-d', $fechaSolicitud->toDateString()), Carbon::createFromFormat('Y-m-d', $fechaEntrega->toDateString()));
                $actualizacion->update(['FechaAprobacion' => $actualizacion->FechaAprobacion, 'FechaLimite' => $fechaLimite, 'estado' => 3, 'aprobador' => Auth::user()->id]);

                investigacionesObservacionesEstado::create([
                    'idInvestigacion' => $investigacion->id,
                    'idUsuario' => Auth::user()->id,
                    'idEstado' => 3,
                    'observacion' => 'Se realiza solicitud de investigación al proveedor.'
                ]);
                break;
            case 19: //devuelto
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->update(['aprobador' => Auth::user()->id]);

				$notificacionTxt = $notificacionTxt . 'DEVUELTA  por el usuario ' . Auth::user()->name . ' ' . Auth::user()->lastname ;
				Notificaciones::create(['idUsuario' => $investigacion->analista, 'mensaje' => $notificacionTxt]);		
				if ($investigacion->analista !=   Auth::user()->id)
					Notificaciones::create(['idUsuario' => Auth::user()->id, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);				
				
				
                break;
            case 8: //Objetado
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->FechaObjecion = now()->timezone('America/Bogota');
                $actualizacion->update(['FechaObjecion' => $actualizacion->FechaObjecion]);
				$notificacionTxt = $notificacionTxt . 'OBJETADA  por el usuario ' . Auth::user()->name . ' ' . Auth::user()->lastname ;
				Notificaciones::create(['idUsuario' => $investigacion->analista, 'mensaje' => $notificacionTxt]);				
				if ($investigacion->analista !=   Auth::user()->id)
					Notificaciones::create(['idUsuario' => Auth::user()->id, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);				
				
                break;
            case 16: //Objetado Finalizado
                $rutaDirectorio = 'investigaciones/finalizadosObjetados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->FechaFinalizacionObjecion = now()->timezone('America/Bogota');
                $actualizacion->update(['FechaObjecion' => $actualizacion->FechaObjecion]);
				$notificacionTxt = $notificacionTxt . 'OBJETADA FINALIZADA  por el usuario ' . Auth::user()->name . ' ' . Auth::user()->lastname ;
				Notificaciones::create(['idUsuario' => $investigacion->analista, 'mensaje' => $notificacionTxt]);				
				if ($investigacion->analista !=   Auth::user()->id)
					Notificaciones::create(['idUsuario' => Auth::user()->id, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);				
				
                if ($this->creacionCarpetaFinalizada($id, 16)) {
                    $this->pdfGenerator->generarInformeInvestigacionPDF($id, 16);
                    return back()->with('info', 'Investigación objetada finalizada correctamente.');
                }
                break;
            case 21: //Aprobado Objetado
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->FechaAprobacionObjecion = now()->timezone('America/Bogota');
                $actualizacion->update(['FechaAprobacionObjecion' => $actualizacion->FechaAprobacionObjecion]);
				$notificacionTxt = $notificacionTxt . 'APROBADA OBJETADA   por el usuario ' . Auth::user()->name . ' ' . Auth::user()->lastname ;
				Notificaciones::create(['idUsuario' => $investigacion->analista, 'mensaje' => $notificacionTxt]);				
				if ($investigacion->analista !=   Auth::user()->id)
					Notificaciones::create(['idUsuario' => Auth::user()->id, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);					
				if ($investigacion->aprobador !=   Auth::user()->id)
					Notificaciones::create(['idUsuario' => Auth::aprobador, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);					
                break;

            case 22: //No aprobado objetado
                if($investigacion->esObjetado == 1){
                    investigacionesObservacionesEstado::create([
                        'idInvestigacion' => $investigacion->id,
                        'idUsuario' => Auth::user()->id,
                        'idEstado' => 16,
                        'observacion' => 'No se acepta objeción y se retorna a Objetado Finalizado la investigación.'
                    ]);
                    $actualizacion = Investigaciones::find($investigacion->id);
                    $actualizacion->update(['estado' => 16]);
                }else{
                    investigacionesObservacionesEstado::create([
                        'idInvestigacion' => $investigacion->id,
                        'idUsuario' => Auth::user()->id,
                        'idEstado' => 7,
                        'observacion' => 'No se acepta objeción y se retorna a finalizada la investigación.'
                    ]);
                    $actualizacion = Investigaciones::find($investigacion->id);
                    $actualizacion->update(['estado' => 7]);
                }
                break;

            case 23: //Aceptación de objeción
                investigacionesObservacionesEstado::create([
                    'idInvestigacion' => $investigacion->id,
                    'idUsuario' => Auth::user()->id,
                    'idEstado' => 23,
                    'observacion' => 'Aceptación de la Objeción, Se retorna la investigación para su revisión y corrección.'
                ]);
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->cantidadObjeciones = $actualizacion->cantidadObjeciones + 1;
                $actualizacion->update(['esObjetado' => 1, 'Prioridad' => '6', 'estado' => 11, 'cantidadObjeciones' => $actualizacion->cantidadObjeciones]);
                break;

            case 24: //No aceptación de objeción
                if($investigacion->esObjetado == 1){
                investigacionesObservacionesEstado::create([
                    'idInvestigacion' => $investigacion->id,
                    'idUsuario' => Auth::user()->id,
                    'idEstado' => 16,
                    'observacion' => 'No se acepta la objeción de la investigación, y se retorna a Objetado Finalizado la investigación.'
                ]);
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->update(['estado' => 7]);
            }else{
                investigacionesObservacionesEstado::create([
                    'idInvestigacion' => $investigacion->id,
                    'idUsuario' => Auth::user()->id,
                    'idEstado' => 7,
                    'observacion' => 'No se acepta la objeción de la investigación, y se retorna a Finalizado la investigación.'
                ]);
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->update(['estado' => 7]);
            }
                break;

            case 25: //No aceptación de objeción pero correccion
                investigacionesObservacionesEstado::create([
                    'idInvestigacion' => $investigacion->id,
                    'idUsuario' => Auth::user()->id,
                    'idEstado' => 11,
                    'observacion' => 'No se acepta la objeción de la investigación, pero se corregirá la información de la investigación.'
                ]);
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->cantidadObjeciones = $actualizacion->cantidadObjeciones + 1;
                $actualizacion->update(['estado' => 11, 'esObjetado' => 1, 'cantidadObjeciones' => $actualizacion->cantidadObjeciones]);
                break;

            case 9: //Cancelado
            case 20: 
                investigacionesObservacionesEstado::create([
                    'idInvestigacion' => $investigacion->id,
                    'idUsuario' => Auth::user()->id,
                    'idEstado' => $request->estado,
                    'observacion' => 'Investigación Cancelada.'
                ]);
                $actualizacion = Investigaciones::find($investigacion->id);
                $actualizacion->update(['estado' => $request->estado]);
				$notificacionTxt = $notificacionTxt . 'CANCELADA por el usuario ' . Auth::user()->name . ' ' . Auth::user()->lastname ;
				Notificaciones::create(['idUsuario' => $investigacion->analista, 'mensaje' => $notificacionTxt]);				
				if ($investigacion->analista !=   Auth::user()->id)
					Notificaciones::create(['idUsuario' => Auth::user()->id, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);					
				if ($investigacion->aprobador !=   Auth::user()->id)
					Notificaciones::create(['idUsuario' => Auth::aprobador, 'mensaje' => $notificacionTxt , 'leido' => 1 , 'pendiente' => 0 ]);									
                break;				
        }

        return back()->with('info', 'Información actualizada correctamente.');
    }


    public function regenerarCarpeta($id)
    {
        $investigacion = Investigaciones::find($id);
        
        $estado = $investigacion->estado;
        if ($estado ==7 && $investigacion->cantidadObjeciones >0) {
            $estado =16;
        }

        if ($investigacion->estado == 7 || $investigacion->estado == 16) {
            if ($this->creacionCarpetaFinalizada($id, $estado)) {
                switch ($estado) {
                    case '7':
                        $rutaDirectorio = 'investigaciones/finalizados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                        $continuar = true;
                        break;
                    case '16':
                        $rutaDirectorio = 'investigaciones/finalizadosObjetados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                        $continuar = true;
                        break;
                }
                //$rutaDirectorio = 'investigaciones/finalizados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;

                if (!Storage::exists($rutaDirectorio)) {
                    Storage::makeDirectory($rutaDirectorio);
                }
                if ($this->pdfGenerator->generarInformeInvestigacionPDF($id, $estado)) {
                    $this->pdfGenerator->generarInformeInvestigacionSoportesPDF($id);
                   
                }
            }
        }

        
    }


    public function creacionCarpetaFinalizada($id, $estado)
    {
        $investigacion = Investigaciones::find($id);
        if ($estado == 7) {
            Storage::disk('investigaciones')->makeDirectory('/finalizados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id);
            Log::channel('stderr')->info('CreateDirectory');
        }
        $rutaDirectorio = '/finalizados/' ;
        switch ($estado) {
            case '7':
                $rutaDirectorio = '/finalizados/' ;
                break;
            case '16':
                $rutaDirectorio = '/finalizadosObjetados/' ;
                break;
        }
        $objetado ='';
        if ($investigacion->cantidadObjeciones>0)
        {
                $objetado = '_' . strval($investigacion->cantidadObjeciones);
                Log::channel('stderr')->info('Cantidad de Objeciones:' . $objetado);
        }
        $archivosInvestigacion = Storage::disk('investigaciones')->allFiles('/radicado/' . $investigacion->nombreCarpeta . '/investigacion');
        $archivosSoporteFotografico = Storage::disk('investigaciones')->allFiles('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico');
        $archivos = array_merge($archivosInvestigacion, $archivosSoporteFotografico);
        $consecutivo = 1;
        foreach ($archivos as $archivo) {
            
            $nombre_archivo = basename($archivo);
            // Log::channel('stderr')->info('Archivo: ' . $nombre_archivo);

            if (!Str::startsWith($nombre_archivo,'DJT-INF') &&  !Str::startsWith($nombre_archivo,'GRP-IAD') ){
                Log::channel('stderr')->info('+ ' . $nombre_archivo);
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                if ($estado == 7 || $estado == 16 ) {
                    $nombreArchivo = 'GRP-IAD-PR-' . $investigacion->CasoPadreOriginal . '_' . date('Ymd') . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . $objetado  . '_'. $consecutivo . '.' . $extension;
                    if (Storage::disk('investigaciones')->copy($archivo, $rutaDirectorio . $investigacion->CasoPadreOriginal . '_' . $investigacion->id . '/' . $nombreArchivo)) {
                        $consecutivo++;
                    }
                }
            }
        }

        return true;
    }


    
    
    public function creacionCarpetaFinalizada2($id, $estado)
    {
        $investigacion = Investigaciones::find($id);
        $carpetaFinalizada = 'investigaciones/nuevas_finalizadas/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
    
        if ($estado == 7) {
            // Verificar si la carpeta ya existe y eliminarla si es así
            if (Storage::exists($carpetaFinalizada)) {
                Storage::deleteDirectory($carpetaFinalizada);
            }
    
            // Crear la nueva carpeta
            Storage::makeDirectory($carpetaFinalizada);
        }
    
        $objetado ='';
        if ($investigacion->cantidadObjeciones>0)
        {
                $objetado = '_' . strval($investigacion->cantidadObjeciones);
        }
        // Obtener los archivos de investigación y soporte fotográfico
        // $archivosBisagy = Storage::files('investigaciones/radicado/' . $investigacion->nombreCarpeta);
        $archivosInvestigacion = Storage::allFiles('investigaciones/radicado/' . $investigacion->nombreCarpeta . '/investigacion');
        $archivosSoporteFotografico = Storage::allFiles('investigaciones/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico');
    
        // Filtrar archivos que no comienzan con "DJT-INF-AD"
        $archivos = array_merge(
            array_filter($archivosInvestigacion, function($archivo) {
                return strpos(basename($archivo), 'DJT-INF-AD') !== 0;
            }),
            array_filter($archivosSoporteFotografico, function($archivo) {
                return strpos(basename($archivo), 'DJT-INF-AD') !== 0;
            })
        );
    
        $consecutivo = 1;
        foreach ($archivos as $archivo) {
            $nombre_archivo = basename($archivo);
            
            if (Str::startsWith($nombre_archivo,'DJT-INF')==false ){
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                if ($estado == 7) {
                    $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
                    $nombreArchivo = 'GRP-IAD-PR-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id  . $objetado . '_' . $consecutivo . '.' . $extension;
                    if (Storage::copy($archivo, $carpetaFinalizada . '/' . $nombreArchivo)) {
                        $consecutivo++;
                    }
                }
            }
        }
        // $consecutivo = 1;
        // foreach($archivosBisagy as $archivo){
            
        //     $nombre_archivo = basename($archivo);
        //     if (Storage::copy($archivo, $carpetaFinalizada . '/Bizagi/' . $nombre_archivo)) {
        //         $consecutivo++;
        //     }
        // }
    
        return true;
    }

 
    public function creacionCarpetaFinalizada3($id, $estado, $carpetaAGuardar='investigaciones/nuevas_finalizadas/', $guardarEnDB = FALSE)
    {
        $observacion = [];
        $tamañoMaximoPermitido = 3 * 1024 * 1024; // 3 MB en bytes
        $investigacion = Investigaciones::find($id);
        // $carpetaFinalizada = $carpetaAGuardar . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
        $documentosParaGuardar = [];  // Inicializa el arreglo
     
        
    

        // Obtener los archivos de las rutas completas
        $archivosInvestigacion = Storage::allFiles('investigaciones/radicado/' . $investigacion->nombreCarpeta . '/investigacion');
        $archivosSoporteFotografico = Storage::allFiles('investigaciones/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico');

        // Filtrar archivos que no comienzan con "DJT-INF-AD"
        $archivos = array_merge(
            array_filter($archivosInvestigacion, function($archivo) {
                return strpos(basename($archivo), 'DJT-INF-AD') !== 0;
            }),
            array_filter($archivosSoporteFotografico, function($archivo) {
                return strpos(basename($archivo), 'DJT-INF-AD') !== 0;
            })
        );
        $folios= null;
        $consecutivo = 1;
        foreach ($archivos as $archivo) {
            $observacion = [];
            $nombre_archivo = basename($archivo);
            if (!Str::startsWith(basename($archivo), 'DJT-INF')) {
                
                $extension = strtolower(pathinfo($nombre_archivo, PATHINFO_EXTENSION));
                // En desarrollo, modulo para el manejo de los archivos opus, tiff, zip
                if($extension == "opus"){
                    $observacion[] = "Es un tipo de archivo OPUS";
                }elseif($extension == "zip" || $extension == "rar" ){
                    $observacion[] = "Es un tipo de archivo zip (comprimido) favor devolver investigacion";
                }elseif($extension == "tif"){
                    // $numero_de_paginas = $this-> contarFoliosTiff(storage_path('app/' . $archivo));
                    // $observacion[] = "Es un tipo de archivo tiff";
                } elseif ($extension == "txt" || $extension == "doc" || $extension == "docx" || $extension == "xlsx" || $extension == "ppt" || $extension == "pptx" || $extension == "odt" || $extension == "ods" || $extension == "odp" || $extension == "rtf" || $extension == "csv") {
                    break;
                }

                $extensionesConocidas = ["pdf",	"mp4",	"mp3",	"jpg",	"jpeg",	"png",	"ogg",	"mpeg",	"aac",	"wav",	"opus", "tif"];

                if (!in_array($extension, $extensionesConocidas)) {
                    $observacion[] = "Tipo de archivo desconocido";
                }


                // $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
                if ($investigacion->estado == 16) {
                    $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacionObjecion));
                } else {
                    $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
                }
                // $nombreArchivoN = 'GRP-IAD-PR-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id  . $objetado . '_' . $consecutivo . '.' . $extension;
                $nombreArchivoN = 'GRP-IAD-PR-' . $investigacion->NumeroRadicacionCaso . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id  . '_' . $consecutivo . '.' . $extension;
                $tamañoArchivo = Storage::size($archivo);

                if ($tamañoArchivo > $tamañoMaximoPermitido) {
                    $observacion[] = "El archivo pesa más de 3 MB";
                    // if($extension == "mp3"){
                    //     $observacion[] = $this->reducirArchivos(storage_path('app/' . $archivo));
                    // }elseif($extension == "jpg"){
                    //     $observacion[] = "El archivo pesa más de 3 MB";
                    // }else{
                    //     $observacion[] = "El archivo pesa más de 3 MB";
                    // }
                    
                }

                $pathDestino = $carpetaAGuardar . $nombreArchivoN;
                
                // Verificar si se puede copiar el archivo correctamente
                if (Storage::copy($archivo, $pathDestino )) {
                    
                    $folios = ($extension == "pdf" || $extension == "tif") ? $this->contarFoliosPdf(storage_path('app/' . $archivo)): 1;
                    if($folios == "error"){
                        $observacion[] = "Contar Folios manualmente, error en el proceso de folios";
                    }
                    $documentosParaGuardar[] = [
                        'idInvestigacion' => $investigacion->id,
                        'NombreNemotecnia' => $nombreArchivoN,
                        'NombreOriginal' => basename($archivo),
                        'CodigoDocumental' => 'GRP-IAD-PR',
                        'peso' => $tamañoArchivo,
                        'folios' => $folios,
                        'observacion' => implode(', ', $observacion), 
                        'created_at' => now()
                    ];

                    $consecutivo++;
                        
                    
                } else {
                    Log::error("Error al copiar el archivo: $nombreArchivoN a $pathDestino");
                }
            }
        }
    
        
        DB::beginTransaction();
        try {
            foreach ($documentosParaGuardar as $documento) {
                generarDocumentacion::create($documento);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error al guardar en la base de datos: " . $e->getMessage());
            throw $e; 
        }
        
        return $consecutivo;
    }
    
    public function creacionCarpetaFinalizada4($id, $estado)
    {
        $investigacion = Investigaciones::find($id);
        $carpetaFinalizada = 'investigaciones/nuevas_finalizadas/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
    
        if ($estado == 7) {
            // Verificar si la carpeta ya existe y eliminarla si es así
            if (Storage::exists($carpetaFinalizada)) {
                Storage::deleteDirectory($carpetaFinalizada);
            }
    
            // Crear la nueva carpeta
            Storage::makeDirectory($carpetaFinalizada);
        }
    
        $objetado ='';
        if ($investigacion->cantidadObjeciones>0)
        {
                $objetado = '_' . strval($investigacion->cantidadObjeciones);
        }
        // Obtener los archivos de investigación y soporte fotográfico
        $archivosInvestigacion = Storage::allFiles('investigaciones/radicado/' . $investigacion->nombreCarpeta . '/investigacion');
        $archivosSoporteFotografico = Storage::allFiles('investigaciones/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico');
    
        // Filtrar archivos que no comienzan con "DJT-INF-AD"
        $archivos = array_merge(
            array_filter($archivosInvestigacion, function($archivo) {
                return strpos(basename($archivo), 'DJT-INF-AD') !== 0;
            }),
            array_filter($archivosSoporteFotografico, function($archivo) {
                return strpos(basename($archivo), 'DJT-INF-AD') !== 0;
            })
        );
    
        $consecutivo = 1;
        foreach ($archivos as $archivo) {
            $nombre_archivo = basename($archivo);
            if (Str::startsWith($nombre_archivo,'DJT-INF')==false &&  Str::startsWith($nombre_archivo,'GRP-IAD')==false ){
                $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                if ($estado == 7) {
                    $nombreArchivo = 'GRP-IAD-PR-' . $investigacion->CasoPadreOriginal . '_' . date('Ymd') . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id  . $objetado . '_' . $consecutivo . '.' . $extension;
                    if (Storage::copy($archivo, $carpetaFinalizada . '/' . $nombreArchivo)) {
                        $consecutivo++;
                    }
                }
            }
        }
    
        return true;
    }
    


    function contarFoliosPdf($filePath) {
        $pythonPath = '"C:\\Users\\Colaborador2\\AppData\\Local\\Programs\\Python\\Python312\\python.exe"';
        $scriptPath = escapeshellarg(storage_path('app/scripts/count_pages.py'));
        $filePath = escapeshellarg($filePath);  
        $command = "$pythonPath $scriptPath $filePath 2>&1";  
        $output = shell_exec($command);
        if ($output === null || strpos(strtolower($output), 'error') !== false) {
            return "error";
        }
        return (int)trim($output);
    }

    // function contarFoliosPdf($filePath) {
    //     $scriptPath = escapeshellarg(storage_path('app/scripts/count_pages.py'));
    //     $filePath = escapeshellarg($filePath);  // Sanitiza la entrada
    //     $command = "python $scriptPath $filePath";
    //     $output = shell_exec($command);
    //     return $output === "error" ? "error contando paginas" : (int)$output;
    // }

    function contarFoliosTiff($filePath) {
        $scriptPath = escapeshellarg(storage_path('app/scripts/count_pages.py'));
        $filePath = escapeshellarg($filePath);  // Sanitizar la entrada
        $command = "python $scriptPath $filePath";
        $output = shell_exec($command);
        return $output === "error" ? "error contando paginas" : (int)$output;
    }
    
    function reducirArchivos($filePath) {
        $scriptPath = escapeshellarg(storage_path('app/scripts/count_pages.py'));
        $filePath = escapeshellarg($filePath);  // Sanitizar la entrada
        $command = "python $scriptPath $filePath";
        $output = shell_exec($command);
        return $output === "error" ? "error contando paginas" : (int)$output;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $investigacion)
    {
        $investigacion = Investigaciones::find($investigacion);
        $investigacion->update($request->all());
        $nombreVariable = 'InstitucionEducativa_';
        $datosRequest = $request->all();
        $cant_beneficiarios = count(array_filter($datosRequest, function ($key) use ($nombreVariable) {
            return strpos($key, $nombreVariable) === 0;
        }, ARRAY_FILTER_USE_KEY));

        if ($cant_beneficiarios > 0) {
            for ($i = 0; $i < $cant_beneficiarios; $i++) {
                if ($request->input('NumeroDocumento_' . $i) !== null) {
                    $beneficiario = InvestigacionesBeneficiarios::create([
                        'IdInvestigacion' => $investigacion->id,
                        'TipoDocumento' =>  $request->input('TipoDocumento_' . $i),
                        'NumeroDocumento' => $request->input('NumeroDocumento_' . $i),
                        'PrimerNombre' => $request->input('PrimerNombre_' . $i),
                        'SegundoNombre' => $request->input('SegundoNombre_' . $i),
                        'PrimerApellido' => $request->input('PrimerApellido_' . $i),
                        'SegundoApellido' => $request->input('SegundoApellido_' . $i),
                        'Parentesco' => $request->input('Parentesco_' . $i),
                        'Nit' => $request->input('Nit_' . $i),
                        'InstitucionEducativa' => $request->input('InstitucionEducativa_' . $i),
                    ]);
                }
            }
        }
        if ($investigacion->estado == 19) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $nombreArchivo = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta, $file, $nombreArchivo);
                }
            }
        } else {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $nombreArchivo = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/investigacion', $file, $nombreArchivo);
                }
            }
        }
        DB::commit();
        return back()->with('info', 'Información actualizada correctamente.');
    }

    public function updateBeneficiarios(Request $request,  $investigacion)
    {

        $beneficiarios = $request->input('beneficiarios', []); // Obtiene todos los beneficiarios, con un valor predeterminado de array vacío
        if(!empty($beneficiarios)){
            
            DB::begintransaction();
            try{
                foreach ($beneficiarios as $datos) {
                    if (!empty($datos['NumeroDocumento'])) { // Verifica que el número de documento no esté vacío
                        $nitBeneficiario = empty($datos['Nit'])? 'null':$datos['Nit'];
                        $institucionEducativaBeneficiario =  empty($datos['InstitucionEducativa'])?  'null':$datos['InstitucionEducativa'];
                        InvestigacionesBeneficiarios::create([
                            'IdInvestigacion' => $investigacion,
                            'TipoDocumento' =>  $datos['TipoDocumento'],
                            'NumeroDocumento' => $datos['NumeroDocumento'],
                            'PrimerNombre' => $datos['PrimerNombre'],
                            'SegundoNombre' => $datos['SegundoNombre'],
                            'PrimerApellido' => $datos['PrimerApellido'],
                            'SegundoApellido' => $datos['SegundoApellido'],
                            'Parentesco' => $datos['Parentesco'],
                            'Nit' => $nitBeneficiario,
                            'InstitucionEducativa' => $institucionEducativaBeneficiario,
                        ]);
                    }
                }
                DB::commit();
            } catch (\Exception $e) {
                echo $e;
                DB::rollBack();
            }
        }
        
        
        return back()->with('info', 'Información actualizada correctamente.');
    }

    public function revisioninvestigacion($id)
    {
        DB::beginTransaction();
        try {
            $parentesco = Parentesco::all();
            $tipoDocumento = TipoDocumento::all();
            $investigacion = Investigaciones::find($id);
            $Historial = Investigaciones::where('TipoDocumento', $investigacion->TipoDocumento)->where('NumeroDeDocumento', $investigacion->NumeroDeDocumento)->get();
            Storage::disk('investigaciones')->makeDirectory('/radicado/' . $investigacion->nombreCarpeta . '/investigacion/');
            // Obtener datos necesarios para la vista
            $facturaAuxilios = [
                [
                    'id' => '1',
                    'name' => 'Factura de Gastos Funerarios'
                ],
                [
                    'id' => '2',
                    'name' => 'Contrato Preexequial'
                ],
                [
                    'id' => '3',
                    'name' => 'Contrato Prenecesidad'
                ],
                [
                    'id' => '4',
                    'name' => 'Póliza de Seguro'
                ],
                [
                    'id' => '0',
                    'name' => 'No aplica'
                ],
            ];
            $causalObjecion = TipoObjecion::all();
            $TipoInvestigacion = TipoInvestigacion::all();
            $InvestigacionPrioridad = TipoPrioridad::all();
            $InvestigacionRegiones = InvestigacionRegion::all();
            $campoSino = States::whereIn('id', [12, 13])->get();
            $seleccionAcreditacion = States::whereIn('id', [14, 15])->get();
            $TipoRiesgo = TipoRiesgo::where('codigo','like',"RC%")->get();
            $TipoPension = TipoPension::all();
            $TipoTramite = TipoTramite::all();
            $DetalleRiesgo = DetalleRiesgo::all();
            $TipoSolicitud = TipoSolicitud::all();
            $TipoSolicitante = TipoSolicitante::all();
            $TipoDocumento = TipoDocumento::all();
            $historialEstados = investigacionesObservacionesEstado::select(
                'investigaciones_observaciones_estados.*',
                'roles.name as rol_usuario'
            )
                ->join('users', 'investigaciones_observaciones_estados.idUsuario', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('investigaciones_observaciones_estados.idInvestigacion', $id)
                ->orderBy('id', 'asc')
                ->get();
            $trazabilidadActividades = TrazabilidadActividadesRealizadas::select(
                'investigacion_trazabilidad_actividades_realizadas.*',
                'roles.name as rol_usuario'
            )
                ->join('users', 'investigacion_trazabilidad_actividades_realizadas.idUsuario', '=', 'users.id')
                ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('investigacion_trazabilidad_actividades_realizadas.idInvestigacion', $id)
                ->orderBy('fecha', 'asc')
                ->get();


            $secciones = SeccionesFormulario::select('secciones.nombre')
                ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
                ->where('investigacion', $investigacion->TipoInvestigacion)
                ->get();
            $estados = [];
            $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
            $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->count();
            if ($entrevistaSolicitante == 0) {
                InvestigacionEntrevistaSolicitante::create(['idInvestigacion' => $id]);
            }
            $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
            $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->count();
            if ($esFraude == 0) {
                InvestigacionFraude::create(['idInvestigacion' => $id, 'fraude' => 13]);
            }
            $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
            foreach ($beneficiarios as $beneficiario) {
                $investigacionVerificacion = InvestigacionVerificacion::where('idBeneficiario', $beneficiario->id)->count();
                if ($investigacionVerificacion === 0) {
                    InvestigacionVerificacion::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $acreditacion = InvestigacionAcreditacion::where('idBeneficiario', $beneficiario->id)->count();
                if ($acreditacion === 0) {
                    InvestigacionAcreditacion::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $acreditaciones = InvestigacionAcreditacion::select('investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->get();
            $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->count();
            if ($entrevistaFamiliares == 0) {
                InvestigacionEntrevistaFamiliares::create(['idInvestigacion' => $id]);
            }
            $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();

            $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->count();
            if ($gastosVivienda == 0) {
                InvestigacionGastosVivienda::create(['idInvestigacion' => $id]);
            }
            $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();

            $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->count();
            if ($auxilioFunerario == 0) {
                InvestigacionAuxilioFunerario::create(['idInvestigacion' => $id]);
            }
            $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();

            $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->count();
            if ($laborCampo == 0) {
                InvestigacionLaborCampo::create(['idInvestigacion' => $id]);
            }
            $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
			
			if ((Auth::user()->roles[0]->id == 9 || Auth::user()->roles[0]->id == 7 ||Auth::user()->roles[0]->id == 1) && $investigacion->estado == 17) { //Pendiente aprobacion
                $estados = States::whereIn('id', [17, 18, 19, 20])->get(); //pendiente de aprobacion, Aprobado, Devolucion, cancelacion
            } elseif ((Auth::user()->roles[0]->id == 12 || Auth::user()->roles[0]->id == 1) && $investigacion->estado == 19) { //Devuelto
                $estados = States::whereIn('id', [17, 19])->get(); //pendiente de aprobacion
            }elseif ((Auth::user()->roles[0]->id == 6 || Auth::user()->roles[0]->id == 2  || Auth::user()->roles[0]->id == 1) && $investigacion->estado == 3) { //solicitado
                $estados = States::whereIn('id', [3, 5])->get(); //solicitado, asignado
            } elseif ((Auth::user()->roles[0]->id == 3 )  && $investigacion->estado == 7) { //Coordinador Operativo / finalizado
                $estados = States::whereIn('id', [7])->get(); //Finalizado
            } elseif ((Auth::user()->roles[0]->id == 3 )  && $investigacion->estado == 16) { //Coordinador Operativo / finalizado Objetado
                $estados = States::whereIn('id', [16])->get(); //Finalizado Objetado
            } elseif ((Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 6  || Auth::user()->roles[0]->id == 4 || Auth::user()->roles[0]->id == 5 || Auth::user()->roles[0]->id == 2 || Auth::user()->roles[0]->id == 1 || Auth::user()->roles[0]->id == 11)  && $investigacion->estado == 5) { //asignado
                $estados = States::whereIn('id', [5, 6])->get(); //asignado, revision
            } elseif ((Auth::user()->roles[0]->id == 11 || Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 6 || Auth::user()->roles[0]->id == 2  || Auth::user()->roles[0]->id == 1) && $investigacion->estado == 6) { //en revision
                if ($investigacion->esObjetado == 0) {
                    $estados = States::whereIn('id', [6, 7, 11])->get(); //revision, finalizado, correccion, cancelado
                } else {
                    $estados = States::whereIn('id', [6, 16, 11])->get(); //revision, finalizado, correccion, cancelado
                }
            } elseif ((Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 6 || Auth::user()->roles[0]->id == 5  || Auth::user()->roles[0]->id == 4 || Auth::user()->roles[0]->id == 2  || Auth::user()->roles[0]->id == 1 || Auth::user()->roles[0]->id == 11)  && $investigacion->estado == 11) { //en correccion
                $estados = States::whereIn('id', [11, 6])->get(); //correcion, revision
            } elseif ((Auth::user()->roles[0]->id == 3 || Auth::user()->roles[0]->id == 2 || Auth::user()->roles[0]->id == 5  || Auth::user()->roles[0]->id == 4 || Auth::user()->roles[0]->id == 6  || Auth::user()->roles[0]->id == 1)  && $investigacion->estado == 21) { //Aprobado Objetado
                $estados = States::whereIn('id', [21, 23, 24, 25])->get(); //Aceptacion, no aceptacion, no aceptacion correccion
            }
            if (Auth::user()->roles[0]->id == 2 || Auth::user()->roles[0]->id == 3) {
                $estados = States::whereIn('id', [3, 5, 6, 7, 9, 11, 23, 24, 25])->get();
            }

            if (Auth::user()->roles[0]->id == 7 && $investigacion->estado == 7) {
                $estados = States::whereBetween('id', [8, 21])->get();
            }

            /*
           if ((Auth::user()->roles[0]->id == 9 || Auth::user()->roles[0]->id == 1) && $investigacion->estado == 17) { //Pendiente aprobacion
                $estados = States::whereIn('id', [17, 18, 19, 20])->get(); //pendiente de aprobacion, Aprobado, Devolucion, cancelacion
            } elseif ((Auth::user()->roles[0]->id == 12 || Auth::user()->roles[0]->id == 1) && $investigacion->estado == 19) { //Devuelto
                $estados = States::whereIn('id', [17, 19])->get(); //pendiente de aprobacion
            } else {
                $estados = States::WhereIn('id', [17, 18, 3, 5, 6, 7, 8, 9, 11, 16, 19, 20, 21])->get();
            } else

            if (Auth::user()->roles[0]->id == 2 || Auth::user()->roles[0]->id == 3) {
                $estados = States::whereBetween('id', [3, 11])->get();
            }
            */
            $estadoCount = false;
            $centrocostos_investigacion = $investigacion->CentroCosto;
            $centrocostos_usuario = Auth::user()->centroCostos->codigo;


            if($centrocostos_investigacion == $centrocostos_usuario || $centrocostos_usuario == 'JA'){
                
                if (count($estados) > 0) {
                    $estadoCount = true;
                } else {
                    $estadoCount = false;
                }
            }


            $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->count();
            if ($validacionDocumentalCausante == 0) {
                InvestigacionesValidacionDocumentalCausante::create(['idInvestigacion' => $id]);
            }
            $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
            $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->count();
            if ($estudioAuxiliar == 0) {
                InvestigacionEstudiosAuxiliares::create(['idInvestigacion' => $id]);
            }
            $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
            foreach ($beneficiarios as $beneficiario) {
                $validacionDocumentalBeneficiario = InvestigacionValidacionDocumentalBeneficiarios::where('idBeneficiario', $beneficiario->id)->count();
                if ($validacionDocumentalBeneficiario == 0) {
                    InvestigacionValidacionDocumentalBeneficiarios::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $escolaridadBeneficiario = InvestigacionEscolaridad::where('idBeneficiario', $beneficiario->id)->count();
                if ($escolaridadBeneficiario == 0) {
                    InvestigacionEscolaridad::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
            $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->count();
            if ($AntecedentesCausante == 0) {
                InvestigacionConsultasAntecedentesCausante::create(['idInvestigacion' => $id]);
            }
            $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
            $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
            foreach ($beneficiarios as $beneficiario) {
                $antecedentesBeneficiario = InvestigacionConsultasAntecedentesBeneficiarios::where('idBeneficiario', $beneficiario->id)->count();
                if ($antecedentesBeneficiario == 0) {
                    InvestigacionConsultasAntecedentesBeneficiarios::create([
                        'idInvestigacion' => $id,
                        'idBeneficiario' => $beneficiario->id
                    ]);
                }
            }
            $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
            $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
            $documentos = Storage::disk('investigaciones')->allFiles('radicado/' . $investigacion->nombreCarpeta);
            $creador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $investigacion->analista)->first();
            $aprobador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $investigacion->aprobador)->first();
            $TipoJuntas = Juntas::all();
            $analistas = User::role('Analista')->selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('users.estado', 1)->orderBy('users.name', 'asc')->get();
            DB::commit();
            return view('investigaciones.revision', compact('tipoDocumento','parentesco','Historial', 'causalObjecion', 'InvestigacionPrioridad', 'TipoJuntas', 'creador', 'aprobador', 'investigacion', 'TipoInvestigacion', 'TipoRiesgo', 'TipoPension', 'TipoTramite', 'DetalleRiesgo', 'TipoSolicitud', 'TipoSolicitante', 'TipoDocumento', 'asignacion', 'beneficiarios', 'documentos', 'estados', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'campoSino', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'seleccionAcreditacion', 'secciones', 'estadoCount', 'InvestigacionRegiones'));
        } catch (\Exception $e) {
            echo $e;
            DB::rollBack();
        }
    }
}

