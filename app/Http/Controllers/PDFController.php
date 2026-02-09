<?php

namespace App\Http\Controllers;

use App\Models\InformesInvestigador;
use App\Models\InvestigacionAcreditacion;
use App\Models\InvestigacionAsignacion;
use App\Models\InvestigacionAuxilioFunerario;
use App\Models\InvestigacionConsultasAntecedentesBeneficiarios;
use App\Models\InvestigacionConsultasAntecedentesCausante;
use App\Models\InvestigacionEntrevistaFamiliares;
use App\Models\InvestigacionEntrevistaSolicitante;
use App\Models\Investigaciones;
use App\Models\InvestigacionesBeneficiarios;
use App\Models\InvestigacionEscolaridad;
use App\Models\investigacionesObservacionesEstado;
use App\Models\InvestigacionEstudiosAuxiliares;
use App\Models\InvestigacionesValidacionDocumentalCausante;
use App\Models\InvestigacionFraude;
use App\Models\InvestigacionGastosVivienda;
use App\Models\InvestigacionLaborCampo;
use App\Models\InvestigacionValidacionDocumentalBeneficiarios;
use App\Models\InvestigacionVerificacion;
use App\Models\Secciones;
use App\Models\SeccionesFormulario;
use App\Models\TrazabilidadActividadesRealizadas;
use App\Models\User;
use App\Models\generarDocumentacion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; 
use setasign\Fpdi\Fpdi;
use setasign\Fpdf\Fpdf;
// use Smalot\PdfParser\Parser;

use PDF;
use Mpdf\Mpdf;

class PDFController extends Controller
{
    public function generarInformeInvestigacionPDF($id, $estado = null)
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode'   => 'utf-8',
            'format' => 'Letter',
            'margin_left'   => 12,
            'margin_right'  => 12,
            'margin_top'    => 12,
            'margin_bottom' => 12,
            'margin_header' => 10,
            'margin_footer' => 10,
            'PDFA'   => true,
            'icc'    => public_path('icc/sRGB2014.icc'),
            'default_font_size' => 12,
            'shrink_tables_to_fit' => 0,
            
            
        ]);

        $logoPath = public_path('images/logo_javh.png');
        $logoBase64 = $this->convertPngToJpegBase64($logoPath);
        $firmaPath = public_path('images/firmaGerente.png');
        $firmaBase64 = $this->convertPngToJpegBase64($firmaPath);
        

        // DB::beginTransaction();
        $investigacion = Investigaciones::find($id);
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
        $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
        $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
        $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
        $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.Parentesco', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.TipoDocumento', 'parentesco.nombre as parentesco', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->leftjoin('parentesco', 'parentesco.codigo', 'investigaciones_beneficiarios.Parentesco')->get();
        $acreditaciones = InvestigacionAcreditacion::select('states.name as estado', 'investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->join('states', 'states.id', 'acreditacion')->get();
        $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();
        $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();
        $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();
        $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
        $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
        $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
        $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
        $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
        $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
        $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
        $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
        $coordinador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->CoordinadorRegional)->first();
        $investigador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Investigador)->first();
        $auxiliar = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Auxiliar)->first();
        $analista = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Analista)->first();
        $secciones = SeccionesFormulario::select('secciones.nombre')
            ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
            ->where('investigacion', $investigacion->TipoInvestigacion)
            ->get();
        if ($investigacion->cantidadObjeciones>0) {
            $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacionObjecion));
            $estado=16;
        }
        else {
            $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
        }
        
        if ($estado == 7) {
            $nombre = 'DJT-INF-AD-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '.pdf';
        } else {
            $nombre = 'DJT-INF-AD-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '_' . $investigacion->cantidadObjeciones . '.pdf';
        }

        // Asegura que las colecciones no sean null
        $validacionDocumentalBeneficiarios = $validacionDocumentalBeneficiarios ?? collect();
        $acreditaciones = $acreditaciones ?? collect();
        $antecedentesBeneficiarios = $antecedentesBeneficiarios ?? collect();
        $trazabilidadActividades = $trazabilidadActividades ?? collect();
        $investigacionVerificacion = $investigacionVerificacion ?? collect();

        // Inicializa objetos que podrían ser null
        $gastosVivienda = $gastosVivienda ?? (object)['totalValor' => 0];
        $entrevistaSolicitante = $entrevistaSolicitante ?? (object)['trabajo_campo' => ''];
        $entrevistaFamiliares = $entrevistaFamiliares ?? (object)['laborCampo' => ''];
        $laborCampo = $laborCampo ?? (object)['laborCampo' => ''];
        $estudioAuxiliar = $estudioAuxiliar ?? (object)[
            'labor' => '', 
            'entrevistaExtrajuicio' => '', 
            'hallazgos' => '', 
            'observacion' => ''
        ];

        //$pdf = PDF::loadView('informes.informePDF', compact('secciones', 'investigacion', 'asignacion', 'beneficiarios', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'nombre'));
        //$pdf->setPaper('letter', 'portrait')->render();
        $logo = base64_encode(file_get_contents(public_path('images/logo_javh.png')));
        $firma = base64_encode(file_get_contents(public_path('images/firmaGerente.png')));
        $imagePath = public_path('images/logo_javh.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $html = view('informes.informePDF', compact('logoBase64', 'firmaBase64','secciones', 'investigacion', 'asignacion', 'beneficiarios', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'nombre'))->render();
        
        $mpdf->WriteHTML($html);
        
        
        $continuar = false;
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
        if ($continuar) {
            $rutaDirectorioRadicado = 'investigaciones/radicado/' . $investigacion->nombreCarpeta;

            $rutaArchivo = $rutaDirectorio . '/' . $nombre;
            $rutaArchivoRadicado = $rutaDirectorioRadicado . '/investigacion/' . $nombre;

            $ruta = 'investigaciones/radicado/' . $investigacion->nombreCarpeta . '/investigacion';

            if (!Storage::exists($ruta)) {
                Storage::makeDirectory($ruta);
            }

            if (!Storage::exists($rutaDirectorio)) {
                Storage::makeDirectory($rutaDirectorio);
            }
            if (!file_exists(storage_path('app/' . $rutaArchivo))) {


                // Guardar PDF en formato PDF/A-1b
                
                $mpdf->Output(storage_path('app/' . $rutaArchivo), 'F');

                
                $mpdf->Output(storage_path('app/' . $rutaArchivoRadicado), 'F');

            }
            
            //dd("adfas");
            // Retornar el PDF para su descarga o visualización
            //return $mpdf->Output('documento_pdfa.pdf', 'I');
            return response()->file(storage_path('app/' . $rutaArchivo));



        }
    }
    private function convertPngToJpegBase64($pngPath)
    {
        // Crea la imagen desde el PNG
        $png = imagecreatefrompng($pngPath);
        if (!$png) {
            throw new Exception("No se pudo crear la imagen desde $pngPath");
        }
        $width  = imagesx($png);
        $height = imagesy($png);
    
        // Crea una imagen true color con fondo blanco
        $jpeg = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($jpeg, 255, 255, 255);
        imagefill($jpeg, 0, 0, $white);
        imagecopy($jpeg, $png, 0, 0, 0, 0, $width, $height);
    
        // Captura la salida JPEG en un buffer
        ob_start();
        imagejpeg($jpeg);
        $jpegData = ob_get_clean();
    
        // Libera memoria
        imagedestroy($png);
        imagedestroy($jpeg);
    
        return base64_encode($jpegData);
    }
    public function generarInformeInvestigacionSoportesPDF($id)
    {
        $investigacion = Investigaciones::find($id);
        $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
        $nombreArchivo = 'GRP-IAD-PR-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '_0.pdf';
        $documentos = Storage::disk('investigaciones')->allFiles('radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico');
        $pdf = PDF::loadView('informes.informeSoportesPDF', compact('documentos', 'nombreArchivo'));

        $pdf->setPaper('letter', 'portrait')->render();

        $ruta = 'investigaciones/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico/';

        if (!Storage::exists($ruta)) {
            Storage::makeDirectory($ruta);
        }
        $estado = $investigacion->estado;
        if ($estado==7 && $investigacion->cantidadObjeciones>0)
        {
            $estado==16;
        }
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

        $rutaArchivo = $rutaDirectorio . '/' . $nombreArchivo;

        if (!Storage::exists($rutaDirectorio)) {
            Storage::makeDirectory($rutaDirectorio);
        }

        // Guardar el archivo PDF en las rutas especificadas
        $pdf->save(storage_path('app/' . $rutaArchivo));

        return true;
    }

    public function generarInformeInvestigacionSoportesPreview($id)
    {
        // DB::beginTransaction();
        $investigacion = Investigaciones::find($id);
        $nombre = 'GRP-INF-AD-' . $investigacion->NumeroRadicacionCaso . '_' . date('Ymd') . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '.pdf';
        $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
        $nombreArchivo = 'GRP-IAD-PR-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '_0.pdf';
        $documentos = Storage::disk('investigaciones')->allFiles('radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico');
        $pdf = PDF::loadView('informes.informeSoportesPDF', compact('documentos', 'nombreArchivo'));

        return $pdf->stream($nombre);


        return true;
    }

    public function verInformePdfFinal($id)
    {
        $investigacion = Investigaciones::find($id);
        if ($investigacion->esObjetado == 0) {
            $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
            $url = "/investigaciones/radicado/" . $investigacion->nombreCarpeta . "/investigacion/DJT-INF-AD-" . $investigacion->CasoPadreOriginal . "_" . $fechaFormateada . "_" . $investigacion->TipoDocumento . "_" . $investigacion->NumeroDeDocumento . "_" . $investigacion->id . ".pdf";
        } else {
            $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacionObjecion));
            $url = "/investigaciones/radicado/" . $investigacion->nombreCarpeta . "/investigacion/DJT-INF-AD-" . $investigacion->CasoPadreOriginal . "_" . $fechaFormateada . "_" . $investigacion->TipoDocumento . "_" . $investigacion->NumeroDeDocumento . "_" . $investigacion->id . '_' . $investigacion->cantidadObjeciones . ".pdf";
        }

        if (!file_exists($url)) {
            return $this->generarInformeInvestigacionPDF($id, $investigacion->estado);
        }

        return $url;
    }

    public function verInformePdfFinalPreview($id)
    {
        // DB::beginTransaction();
        $investigacion = Investigaciones::find($id);
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
        $nombre = 'informePreview.pdf';
        $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
        $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
        $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
        $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.Parentesco', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.TipoDocumento', 'parentesco.nombre as parentesco', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->leftjoin('parentesco', 'parentesco.codigo', 'investigaciones_beneficiarios.Parentesco')->get();
        $acreditaciones = InvestigacionAcreditacion::select('states.name as estado', 'investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->join('states', 'states.id', 'acreditacion')->get();
        $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();
        $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();
        $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();
        $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
        $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
        $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
        $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
        $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
        $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
        $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
        $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
        $coordinador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->CoordinadorRegional)->first();
        $investigador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Investigador)->first();
        $auxiliar = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Auxiliar)->first();
        $analista = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Analista)->first();
        $secciones = SeccionesFormulario::select('secciones.nombre')
            ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
            ->where('investigacion', $investigacion->TipoInvestigacion)
            ->get();
        $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
        $pdf = PDF::loadView('informes.informePDF', compact('secciones', 'investigacion', 'asignacion', 'beneficiarios', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'nombre'));
        $pdf->setPaper('letter', 'portrait')
            ->setOption('margin-top', 30)
            ->setOption('margin-bottom', 30)
            ->setOption('margin-left', 15)
            ->setOption('margin-right', 15);

        return $pdf->stream('informePreview.pdf');
    }


    public function generarInformeInvestigacionSoportesPDF2($id, $carpetaAGuardar='investigaciones/nuevas_finalizadas/', $guardarEnDB = FALSE, $contadorSoportes =0)
    {
        $investigacion = Investigaciones::find($id);
        $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
        $nombreArchivo = 'GRP-IAD-PR-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id .'_'.$contadorSoportes. '.pdf';
        $documentos = Storage::disk('investigaciones')->allFiles('radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico');
        $pdf = PDF::loadView('informes.informeSoportesPDF', compact('documentos', 'nombreArchivo'));

        $pdf->setPaper('letter', 'portrait')->render();
        $guardarEnUnDirectorio = ($guardarEnDB == TRUE)? "": $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
        $rutaDirectorio = $carpetaAGuardar . $guardarEnUnDirectorio;
        $rutaArchivo = $rutaDirectorio . '/' . $nombreArchivo;

        if (!Storage::exists($rutaDirectorio)) {
            Storage::makeDirectory($rutaDirectorio);
        }

        // Guardar el archivo PDF en la ruta especificada
        $pdf->save(storage_path('app/' . $rutaArchivo));
        $CodigoDocumental = "GRP-IAD-PR";
        $this ->guardarEnDB($rutaArchivo, $investigacion->id, $nombreArchivo, $guardarEnDB, $CodigoDocumental);
        return true;
    }

    public function generarInformeInvestigacionPDF2($id, $estado = null)
    {
        // DB::beginTransaction();
        $investigacion = Investigaciones::find($id);
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
        $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
        $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
        $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
        $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.Parentesco', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.TipoDocumento', 'parentesco.nombre as parentesco', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->leftjoin('parentesco', 'parentesco.codigo', 'investigaciones_beneficiarios.Parentesco')->get();
        $acreditaciones = InvestigacionAcreditacion::select('states.name as estado', 'investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->join('states', 'states.id', 'acreditacion')->get();
        $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();
        $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();
        $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();
        $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
        $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
        $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
        $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
        $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
        $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
        $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
        $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
        $coordinador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->CoordinadorRegional)->first();
        $investigador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Investigador)->first();
        $auxiliar = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Auxiliar)->first();
        $analista = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Analista)->first();
        $secciones = SeccionesFormulario::select('secciones.nombre')
            ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
            ->where('investigacion', $investigacion->TipoInvestigacion)
            ->get();
        $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
        if ($estado == 7) {
            $nombre = 'DJT-INF-AD-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '.pdf';
        } else {
            $nombre = 'DJT-INF-AD-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '_' . $investigacion->cantidadObjeciones . '.pdf';
        }
        $pdf = PDF::loadView('informes.informePDF', compact('secciones', 'investigacion', 'asignacion', 'beneficiarios', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'nombre'));
        $pdf->setPaper('letter', 'portrait')->render();
        $continuar = false;
        switch ($estado) {
            case '7':
                $rutaDirectorio = 'investigaciones/nuevas_finalizadas/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                $continuar = true;
                break;
            case '16':
                $rutaDirectorio = 'investigaciones/nuevas_finalizadas/finalizadosObjetados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                $continuar = true;
                break;
        }
        if ($continuar) {
            $rutaDirectorioRadicado = 'investigaciones/nuevas_finalizadas/' . $investigacion->nombreCarpeta;
            var_dump($rutaDirectorioRadicado);
            $rutaArchivo = $rutaDirectorio . '/' . $nombre;
            var_dump($rutaArchivo);
            $rutaArchivoRadicado = $rutaDirectorioRadicado . '/' .$nombre;
            var_dump($rutaArchivoRadicado);

            // if (!Storage::exists($rutaDirectorioRadicado)) {
            //     Storage::makeDirectory($rutaDirectorioRadicado);
            // }
            if (!Storage::exists($rutaDirectorio)) {
                Storage::makeDirectory($rutaDirectorio);
            }
            if (!file_exists(storage_path('app/' . $rutaArchivo))) {
                $pdf->save(storage_path('app/' . $rutaArchivo));
                // $pdf->save(storage_path('app/' . $rutaArchivoRadicado));
            }
            return $pdf->stream($nombre);
        }
    }
    public function generarInformeInvestigacionPDF3($id, $estado = null, $carpetaAGuardar='investigaciones/nuevas_finalizadas/', $guardarEnDB = FALSE, $makeJustReports = FALSE)
    {
        $mpdf = new \Mpdf\Mpdf([
            'mode'   => 'utf-8',
            'format' => 'Letter',
            'margin_left'   => 12,
            'margin_right'  => 12,
            'margin_top'    => 12,
            'margin_bottom' => 12,
            'margin_header' => 10,
            'margin_footer' => 10,
            'PDFA'   => true,
            'icc'    => public_path('icc/sRGB2014.icc'),
            'default_font_size' => 12,
            'shrink_tables_to_fit' => 0,
            
        ]);

        $logoPath = public_path('images/logo_javh.png');
        $logoBase64 = $this->convertPngToJpegBase64($logoPath);
        $firmaPath = public_path('images/firmaGerente.png');
        $firmaBase64 = $this->convertPngToJpegBase64($firmaPath);
        
        $observacion = [];
        
        $investigacion = Investigaciones::find($id);
        if($investigacion->NumeroRadicacionCaso != $investigacion->CasoPadreOriginal || $investigacion->NumeroRadicacionCaso == $investigacion->NumeroDeDocumento || $investigacion->NumeroRadicacionCaso == $investigacion->TelefonoCausante){
            $observacion[] = "Revisar que el número de radicadoCaso";
        }
        $facturaAuxilios = [
            [ 'id' => '1',  'name' => 'Factura de Gastos Funerarios'    ],
            [ 'id' => '2',  'name' => 'Contrato Preexequial'            ],
            [ 'id' => '3',  'name' => 'Contrato Prenecesidad'           ],
            [ 'id' => '4',  'name' => 'Póliza de Seguro'                ],
            [ 'id' => '0',  'name' => 'No aplica'                       ],
        ];
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
        $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
        $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
        $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
        $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.Parentesco', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.TipoDocumento', 'parentesco.nombre as parentesco', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->leftjoin('parentesco', 'parentesco.codigo', 'investigaciones_beneficiarios.Parentesco')->get();
        $acreditaciones = InvestigacionAcreditacion::select('states.name as estado', 'investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->join('states', 'states.id', 'acreditacion')->get();
        $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();
        $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();
        $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();
        $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
        $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
        $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
        $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
        $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
        $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
        $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
        $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
        $coordinador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->CoordinadorRegional)->first();
        $investigador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Investigador)->first();
        $auxiliar = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Auxiliar)->first();
        $analista = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Analista)->first();
        $secciones = SeccionesFormulario::select('secciones.nombre')
            ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
            ->where('investigacion', $investigacion->TipoInvestigacion)
            ->get();
        
        if ($investigacion->estado == 16) {
            $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacionObjecion));
            $nombre = 'DJT-INF-AD-' . $investigacion->NumeroRadicacionCaso . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '.pdf';
        } else {
            $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
            $nombre = 'DJT-INF-AD-' . $investigacion->NumeroRadicacionCaso . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '.pdf';
        }
        // $pdf = PDF::loadView('informes.informePDF', compact('logoBase64', 'firmaBase64','logo', 'firma', 'secciones', 'investigacion', 'asignacion', 'beneficiarios', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'nombre'));
        // $pdf->setPaper('letter', 'portrait')->render();
        // En tu controlador, antes de pasar datos a la vista
        if (empty($acreditaciones)) {
            $acreditaciones = collect(); // Asegura que sea una colección vacía en lugar de null
        }
        // Asegura que las colecciones no sean null
        $validacionDocumentalBeneficiarios = $validacionDocumentalBeneficiarios ?? collect();
        $acreditaciones = $acreditaciones ?? collect();
        $antecedentesBeneficiarios = $antecedentesBeneficiarios ?? collect();
        $trazabilidadActividades = $trazabilidadActividades ?? collect();
        $investigacionVerificacion = $investigacionVerificacion ?? collect();

        // Inicializa objetos que podrían ser null
        $gastosVivienda = $gastosVivienda ?? (object)['totalValor' => 0];
        $entrevistaSolicitante = $entrevistaSolicitante ?? (object)['trabajo_campo' => ''];
        $entrevistaFamiliares = $entrevistaFamiliares ?? (object)['laborCampo' => ''];
        $laborCampo = $laborCampo ?? (object)['laborCampo' => ''];
        $estudioAuxiliar = $estudioAuxiliar ?? (object)[
            'labor' => '', 
            'entrevistaExtrajuicio' => '', 
            'hallazgos' => '', 
            'observacion' => ''
        ];
        $logo = base64_encode(file_get_contents(public_path('images/logo_javh.png')));
        $firma = base64_encode(file_get_contents(public_path('images/firmaGerente.png')));
        $imagePath = public_path('images/logo_javh.png');
        $imageData = base64_encode(file_get_contents($imagePath));
        $html = view('informes.informeGenerarDocumentacion', compact('logoBase64', 'firmaBase64','logo', 'firma', 'secciones', 'investigacion', 'asignacion', 'beneficiarios', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'nombre'))->render();
        $mpdf->WriteHTML($html);
        
        
        $continuar = false;
        $guardarEnUnDirectorio = ($guardarEnDB == TRUE)? "":$investigacion->CasoPadreOriginal . '_' . $investigacion->id;
        switch ($estado) {
            case '7':
                $rutaDirectorio = $carpetaAGuardar . $guardarEnUnDirectorio;
                $continuar = true;
                break;
            case '16':
                $rutaDirectorio = $carpetaAGuardar. 'finalizadosObjetados/' . $guardarEnUnDirectorio;
                $continuar = true;
                break;
        }

        

        if ($continuar) {

            $rutaArchivo = $rutaDirectorio  . $nombre;

            if (!Storage::exists($rutaDirectorio)) {
                Storage::makeDirectory($rutaDirectorio);
            }
            
            if (!file_exists(storage_path('app/' . $rutaArchivo))) {
                $mpdf->Output(storage_path('app/' . $rutaArchivo), 'F');
                // $pdf->save(storage_path('app/' . $rutaArchivo));
            }
            
            $CodigoDocumental =  "DJT-INF-AD";
            if(!$makeJustReports){
                $this ->guardarEnDB($rutaArchivo, $investigacion->id,$nombre, $guardarEnDB,$CodigoDocumental, $observacion);
            }
            
            
            return response()->file(storage_path('app/' . $rutaArchivo));
        }
    }

    public function generarInformeInvestigacionPDF4($id, $estado = null)
    {
        DB::beginTransaction();
        $investigacion = Investigaciones::find($id);
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
        $beneficiarios = InvestigacionesBeneficiarios::where('IdInvestigacion', $id)->get();
        $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::where('idInvestigacion', $id)->first();
        $esFraude = InvestigacionFraude::where('idInvestigacion', $id)->first();
        $investigacionVerificacion = InvestigacionVerificacion::select('investigacion_verificacion.*', 'investigaciones_beneficiarios.Parentesco', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.TipoDocumento', 'parentesco.nombre as parentesco', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_verificacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_verificacion.idBeneficiario')->leftjoin('parentesco', 'parentesco.codigo', 'investigaciones_beneficiarios.Parentesco')->get();
        $acreditaciones = InvestigacionAcreditacion::select('states.name as estado', 'investigacion_acreditacion.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_acreditacion.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_acreditacion.idBeneficiario')->join('states', 'states.id', 'acreditacion')->get();
        $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::where('idInvestigacion', $id)->first();
        $gastosVivienda = InvestigacionGastosVivienda::where('idInvestigacion', $id)->first();
        $auxilioFunerario = InvestigacionAuxilioFunerario::where('idInvestigacion', $id)->first();
        $laborCampo = InvestigacionLaborCampo::where('idInvestigacion', $id)->first();
        $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::where('idInvestigacion', $id)->first();
        $estudioAuxiliar = InvestigacionEstudiosAuxiliares::where('idInvestigacion', $id)->first();
        $validacionDocumentalBeneficiarios = InvestigacionValidacionDocumentalBeneficiarios::select('investigaciones_validacion_documental_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigaciones_validacion_documental_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigaciones_validacion_documental_beneficiarios.idBeneficiario')->get();
        $escolaridadBeneficiarios = InvestigacionEscolaridad::select('investigacion_escolaridad.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_escolaridad.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_escolaridad.idBeneficiario')->get();
        $AntecedentesCausante = InvestigacionConsultasAntecedentesCausante::where('idInvestigacion', $id)->first();
        $antecedentesBeneficiarios = InvestigacionConsultasAntecedentesBeneficiarios::select('investigacion_consultas_antecedentes_beneficiarios.*', 'investigaciones_beneficiarios.NumeroDocumento', 'investigaciones_beneficiarios.PrimerNombre', 'investigaciones_beneficiarios.SegundoNombre', 'investigaciones_beneficiarios.PrimerApellido', 'investigaciones_beneficiarios.SegundoApellido')->where('investigacion_consultas_antecedentes_beneficiarios.idInvestigacion', $id)->join('investigaciones_beneficiarios', 'investigaciones_beneficiarios.id', 'investigacion_consultas_antecedentes_beneficiarios.idBeneficiario')->get();
        $asignacion = InvestigacionAsignacion::where('idInvestigacion', $id)->first();
        $coordinador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->CoordinadorRegional)->first();
        $investigador = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Investigador)->first();
        $auxiliar = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Auxiliar)->first();
        $analista = User::selectRaw('id, CONCAT(users.name, " ", users.lastname) as full_name')->where('id', $asignacion->Analista)->first();
        $secciones = SeccionesFormulario::select('secciones.nombre')
            ->join('secciones', 'secciones.id', '=', 'secciones_formularios.Seccion')
            ->where('investigacion', $investigacion->TipoInvestigacion)
            ->get();
        $fechaFormateada = date('Ymd', strtotime($investigacion->FechaFinalizacion));
        if ($estado == 7) {
            $nombre = 'DJT-INF-AD-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '.pdf';
        } else {
            $nombre = 'DJT-INF-AD-' . $investigacion->CasoPadreOriginal . '_' . $fechaFormateada . '_' . $investigacion->TipoDocumento . '_' . $investigacion->NumeroDeDocumento . '_' . $investigacion->id . '_' . $investigacion->cantidadObjeciones . '.pdf';
        }
        $pdf = PDF::loadView('informes.informePDF', compact('secciones', 'investigacion', 'asignacion', 'beneficiarios', 'validacionDocumentalCausante', 'validacionDocumentalBeneficiarios', 'AntecedentesCausante', 'antecedentesBeneficiarios', 'historialEstados', 'trazabilidadActividades', 'entrevistaSolicitante', 'facturaAuxilios', 'auxilioFunerario', 'gastosVivienda', 'laborCampo', 'entrevistaFamiliares', 'escolaridadBeneficiarios', 'acreditaciones', 'coordinador', 'investigador', 'auxiliar', 'analista', 'investigacionVerificacion', 'esFraude', 'estudioAuxiliar', 'nombre'));
        $pdf->setPaper('letter', 'portrait')->render();
        $continuar = false;
        switch ($estado) {
            case '7':
                $rutaDirectorio = 'investigaciones/nuevas_finalizadas/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                var_dump("investigacion finalizada: ", $id, "carpeta: ", "$rutaDirectorio");
                $continuar = true;
                break;
            case '16':
                $rutaDirectorio = 'investigaciones/nuevas_finalizadas/finalizadosObjetados/' . $investigacion->CasoPadreOriginal . '_' . $investigacion->id;
                var_dump("investigacion finalizada objetada: ", $id, "carpeta: ", "$rutaDirectorio");
                $continuar = true;
                break;
        }
        if ($continuar) {
            $rutaDirectorioRadicado = 'investigaciones/nuevas_finalizadas/' . $investigacion->nombreCarpeta;
            var_dump($rutaDirectorioRadicado);
            $rutaArchivo = $rutaDirectorio . '/' . $nombre;
            var_dump($rutaArchivo);
            $rutaArchivoRadicado = $rutaDirectorioRadicado . '/' .$nombre;
            var_dump($rutaArchivoRadicado);

            // if (!Storage::exists($rutaDirectorioRadicado)) {
            //     Storage::makeDirectory($rutaDirectorioRadicado);
            // }

            if (!Storage::exists($rutaDirectorio)) {
                Storage::makeDirectory($rutaDirectorio);
            }

            if (!file_exists(storage_path('app/' . $rutaArchivo))) {
                $pdf->save(storage_path('app/' . $rutaArchivo));
                // $pdf->save(storage_path('app/' . $rutaArchivoRadicado));
            }
            return $pdf->stream($nombre);
        }
    }
    private function guardarEnDB($rutaArchivo, $id, $nombre, $guardarEnDB, $CodigoDocumental, $observacion){
        
        $tamañoArchivo = Storage::size( $rutaArchivo, $id);
                if($guardarEnDB){
                    DB::beginTransaction();
                    try {
                        
                        $folios =  $this->contarFoliosPdf(storage_path('app/' . $rutaArchivo));
                        
                        generarDocumentacion::create([
                            'idInvestigacion' => $id,
                            'NombreNemotecnia' => $nombre,
                            'NombreOriginal' => 'NULL',
                            'CodigoDocumental' => $CodigoDocumental,
                            'peso' => $tamañoArchivo,
                            'folios' => $folios,
                            'observacion' => implode(', ', $observacion), 
                            'created_at' => now()
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        DB::rollBack();
                        //Log::error("Error al guardar en la base de datos: " . $e->getMessage());
                        throw $e;  // Lanza la excepción para manejarla a un nivel superior si es necesario
                    }
                    
                }
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
    

    
    public function verinformeInvestigadorpdf(string $id ,string $periodo1 , string $periodo2)
    {
        
          $sql = 'SELECT   UCASE(CONCAT_WS(\' \' , NAME ,lastname )) AS nombres, numberDocument AS identificacion FROM users ' .
                'WHERE id = ' . $id ;

            $periodo = $periodo1 . ' a ' . $periodo2;
        
        
           $sql2 = 'select investigaciones.id AS idInvestigacion, ' .
                'investigaciones.NumeroRadicacionCaso, ' .
                'tipo_investigacion.nombre AS tipoInvestigacion, ' .
                'investigaciones_comision.porBeneficiario '.
                'FROM investigaciones LEFT JOIN ' .
                'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
                'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
                'investigaciones_comision ON investigaciones_comision.idInvestigacion = investigaciones.id LEFT JOIN ' .
                'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
                'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador  ' .
                'WHERE   not investigaciones_facturacion.FechaFacturacion is NULL and   Investigador.id =  ' . $id  .' and  Investigaciones.FechaFinalizacion between  \'' . $periodo1 . '\' and DATE_ADD(\'' . $periodo2 . '\', INTERVAL 1 DAY) ' . 
                ' group by tipoInvestigacion , investigaciones.NumeroRadicacionCaso ,  investigaciones.id  order by tipoInvestigacion  ' ;
        
         $sql3 = 'select   tipo_investigacion.nombre AS tipoInvestigacion, count(*) as cantidad ' .
                'FROM investigaciones LEFT JOIN ' .
                'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
                'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
                'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
                'users as Investigador  ON Investigador.id = investigacion_asignacion.Investigador LEFT JOIN   ' .
                'users as Auxiliar  ON Investigador.id = investigacion_asignacion.Auxiliar  ' .
                'WHERE   not investigaciones_facturacion.FechaFacturacion is NULL and ( Investigador.id =  ' . $id  .' or Auxiliar.id =  ' . $id  .' ) and  Investigaciones.FechaFinalizacion between \'' . $periodo1 . '\' and DATE_ADD(\'' . $periodo2 . '\', INTERVAL 1 DAY) ' .
                ' group by tipoInvestigacion  order by tipoInvestigacion ' ;
        
        
        
           $sql4 = 'select investigaciones.id AS idInvestigacion, ' .
                'investigaciones.NumeroRadicacionCaso, ' .
                'tipo_investigacion.nombre AS tipoInvestigacion ' .
                
                'FROM investigaciones LEFT JOIN ' .
                'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
                'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
                'investigaciones_facturacion ON investigaciones.id = investigaciones_facturacion.idInvestigacion LEFT JOIN ' .
                'users as Investigador  ON Investigador.id = investigacion_asignacion.Auxiliar  ' .
                'WHERE   not investigaciones_facturacion.FechaFacturacion is NULL and   Investigador.id =  ' . $id  .' and  Investigaciones.FechaFinalizacion between  \'' . $periodo1 . '\' and DATE_ADD(\'' . $periodo2 . '\', INTERVAL 1 DAY) ' . 
                ' group by tipoInvestigacion , investigaciones.NumeroRadicacionCaso ,  investigaciones.id  order by tipoInvestigacion  ' ;
        
        
        
        $contratista = collect(DB::Select($sql))->first();;
        $datos = DB::Select($sql3);
        $investigaciones = DB::Select($sql2);
        $apoyos = DB::Select($sql4);
        


        $pdf = PDF::loadView('informes.informeInvestigadorPDF', compact('contratista', 'datos', 'investigaciones', 'apoyos', 'periodo'));
        $pdf->setPaper('letter', 'portrait')->render();
            $rutaArchivo = 'informeInvestigador' . $id. '-' . $periodo .'.pdf' ;

            
            return $pdf->stream($rutaArchivo);

    }




    public function renderInforme(string $id )
        {
            
            
              
            $InformesInvestigador = InformesInvestigador::where('id', $id)->first();
                    
            $sql = 'SELECT   UCASE(CONCAT_WS(\' \' , NAME ,lastname )) AS nombres, numberDocument AS identificacion , firma ,coordinador FROM users ' .
                    'WHERE id = ' . $InformesInvestigador->idInvestigador ;
            $contratista = collect(DB::Select($sql))->first();
            $periodo = $InformesInvestigador->Inicio . ' a ' . $InformesInvestigador->Fin;
            $coordinador = null;
            if ($contratista->coordinador>0){
                $sqlc = 'SELECT   UCASE(CONCAT_WS(\' \' , NAME ,lastname )) AS nombres, numberDocument AS identificacion , firma ,coordinador FROM users ' .
                    'WHERE id = ' . $contratista->coordinador ;
                $coordinador= collect(DB::Select($sqlc))->first();
                
            }
            
            
            $sql2 = 'select investigaciones.id AS idInvestigacion, ' .
                    'investigaciones.NumeroRadicacionCaso, ' .
                    'tipo_investigacion.nombre AS tipoInvestigacion, ' .
                    'investigaciones_comision.porBeneficiario '.
                    'FROM investigaciones LEFT JOIN ' .
                    'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
                    'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
                    'investigaciones_comision ON investigaciones_comision.idInvestigacion = investigaciones.id ' .
                    'WHERE investigaciones_comision.idInformeInvestigador = ' . $id . ' order by tipo_investigacion.nombre' ;
            
             $sql3 = 'select tipo_investigacion.nombre AS tipoInvestigacion , sum( case when investigaciones_comision.idInformeInvestigador= ' . $id . ' then 1 else 0 end ) as cantidad ' .
                    'FROM investigaciones LEFT JOIN ' .
                    'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
                    'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
                    'investigaciones_comision ON investigaciones_comision.idInvestigacion = investigaciones.id  ' .
                    'WHERE investigaciones_comision.idInformeInvestigador = ' . $id . ' or  investigaciones_comision.idInformeAuxiliar = ' . $id . ' group  by tipo_investigacion.nombre' ;
            
            
            
               $sql4 = 'select investigaciones.id AS idInvestigacion, ' .
                    'investigaciones.NumeroRadicacionCaso, ' .
                    'tipo_investigacion.nombre AS tipoInvestigacion, ' .
                    'investigaciones_comision.porBeneficiario '.
                    'FROM investigaciones LEFT JOIN ' .
                    'tipo_investigacion ON investigaciones.TipoInvestigacion = tipo_investigacion.codigo LEFT JOIN ' .
                    'investigacion_asignacion ON investigacion_asignacion.idInvestigacion = investigaciones.id LEFT JOIN ' .
                    'investigaciones_comision ON investigaciones_comision.idInvestigacion = investigaciones.id ' .
                    'WHERE investigaciones_comision.idInformeAuxiliar = ' . $id . ' order by tipo_investigacion.nombre';
            
            
            
            
            $datos = DB::Select($sql3);
            $investigaciones = DB::Select($sql2);
            $apoyos = DB::Select($sql4);
            $supervisor = 


            $pdf = PDF::loadView('informes.informeInvestigadorAprobadoPDF', compact('contratista', 'datos', 'investigaciones', 'apoyos', 'periodo','InformesInvestigador','coordinador'));
            $pdf->setPaper('letter', 'portrait')->render();
                

                
                return $pdf;

        }



    public function verinformeInvestigadorpdf2(string $id )
    {
        
        $InformesInvestigador = InformesInvestigador::where('id', $id)->first();
                    
            $sql = 'SELECT   UCASE(CONCAT_WS(\' \' , NAME ,lastname )) AS nombres, numberDocument AS identificacion , firma ,coordinador FROM users ' .
                    'WHERE id = ' . $InformesInvestigador->idInvestigador ;
            $contratista = collect(DB::Select($sql))->first();
            $periodo = $InformesInvestigador->Inicio . ' a ' . $InformesInvestigador->Fin;
          
        
        $pdf = $this->renderInforme($id);
            $rutaArchivo = 'informeInvestigador' . $id. '-' . $periodo .'.pdf' ;

            
            return $pdf->stream($rutaArchivo);

    }



}



