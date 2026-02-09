<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\InformesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvestigacionValidacionDocumentalBeneficiariosController;
use App\Http\Controllers\InvestigacionAsignacionController;
use App\Http\Controllers\InvestigacionAuxilioFunerarioController;
use App\Http\Controllers\InvestigacionConsultasAntecedentesBeneficiariosController;
use App\Http\Controllers\InvestigacionConsultasAntecedentesCausanteController;
use App\Http\Controllers\InvestigacionEntrevistaFamiliaresController;
use App\Http\Controllers\InvestigacionesController;
use App\Http\Controllers\InvestigacionesFacturacionController;
use App\Http\Controllers\InvestigacionesRadicacionController;
use App\Http\Controllers\InvestigacionesValidacionDocumentalCausanteController;
use App\Http\Controllers\InvestigacionGastosViviendaController;
use App\Http\Controllers\InvestigacionLaborCampoController;
use App\Http\Controllers\InvestigacionEntrevistaSolicitanteController;
use App\Http\Controllers\InvestigacionEscolaridadController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PermisosController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\TrazabilidadActividadesRealizadasController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CentroCostosController;
use App\Http\Controllers\ControlDiasFestivosController;
use App\Http\Controllers\InvestigacionAcreditacionController;
use App\Http\Controllers\InvestigacionesBeneficiariosController;
use App\Http\Controllers\InvestigacionEstudiosAuxiliaresController;
use App\Http\Controllers\InvestigacionFraudeController;
use App\Http\Controllers\InvestigacionRegion;
use App\Http\Controllers\InvestigacionVerificacionController;
use App\Http\Controllers\NovedadController;
use App\Http\Controllers\NotificacionesController;
use App\Http\Controllers\SeccionesController;
use App\Http\Controllers\SeccionesFormularioController;
use App\Mail\OlvidoContrasenaMail;
use App\Mail\TestMail;
use App\Models\InvestigacionesBeneficiarios;
use App\Models\Novedad;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\CarpetasController;
use App\Http\Controllers\PDFINVESTIGController;
use App\Http\Controllers\generarDocumentacion;


use App\Http\Controllers\AsignacionMasivaAnalistasController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//Auth::routes();
Route::get('/password/reset', [UserController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [UserController::class, 'sendResetLinkEmail'])->name('password.email');

/* Route::get('forgot-password', UserController::class, 'showLinkRequestForm')->name('password.request');
Route::post('forgot-password', UserController::class, 'sendResetLinkEmail')->name('password.email');
Route::get('reset-password/{token}', UserController::class, 'showResetForm')->name('password.reset');
Route::post('reset-password', UserController::class, 'reset')->name('password.update'); */

Route::group(['middleware' => 'checkRules'], function () {

    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/', [DashboardController::class, 'index'])->middleware(['auth']);
    Route::get('/home', [DashboardController::class, 'index'])->name('home')->middleware(['auth']);
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth']);

    Route::resource('usuarios', UserController::class)->names('user')->middleware(['auth']);
    Route::resource('permisos', PermisosController::class)->names('permisos')->middleware(['auth']);
    Route::resource('roles', RolesController::class)->names('roles')->middleware(['auth']);
    Route::resource('servicios', ServiciosController::class)->names('servicios')->middleware(['auth']);
    Route::resource('documentos', DocumentosController::class)->names('documentos')->middleware(['auth']);
    Route::resource('validaciondocumentalsolicitante', InvestigacionesValidacionDocumentalCausanteController::class)->names('validaciondocumentalsolicitante')->middleware(['auth']);
    Route::resource('validaciondocumentalbeneficiario', InvestigacionValidacionDocumentalBeneficiariosController::class)->names('validaciondocumentalbeneficiario')->middleware(['auth']);

    Route::delete('/beneficiarios/eliminar', [InvestigacionesBeneficiariosController::class, 'eliminarBeneficarioRevision'])->name('eliminarBeneficarioRevision');
    Route::put('updateBeneficiarios/{id}', [InvestigacionesController::class, 'updateBeneficiarios'])->name('updateBeneficiarios')->middleware(['auth']);

    Route::resource('investigacion', InvestigacionesController::class)->names('investigacion')->middleware(['auth']);
    Route::get('investigacionesLista/{id?}', [InvestigacionesController::class, 'investigacionesTrazabilidad'])->name('investigacionesLista')->middleware(['auth']);

    Route::get('misinvestigaciones/{estado?}', [InvestigacionesController::class, 'misInvestigaciones'])->middleware(['auth']);
    Route::get('migrupo/{estado?}', [InvestigacionesController::class, 'migrupo'])->middleware(['auth']);

    Route::get('miCentroCosto/{estado?}', [InvestigacionesController::class, 'miCentroCosto'])->middleware(['auth']);

    Route::get('investigacionesTodas/{estado?}', [InvestigacionesController::class, 'investigacionesTodas'])->name('investigacionesTodas')->middleware(['auth']);

    Route::get('buscarInvestigacion', [InvestigacionesController::class, 'buscarInvestigacion'])->name('buscarInvestigacion')->middleware(['auth']);

    Route::get('trazabilidadcolpensiones', [InvestigacionesController::class, 'indexColpensionesTabs'])->name('trazabilidadcolpensiones')->middleware(['auth']);

    Route::get('consultavalidacioninvestigacion', [InvestigacionesController::class, 'consultaValidacionInvestigacion'])->name('consultavalidacioninvestigacion')->middleware(['auth']);
    Route::post('consultavalidacioninvestigacion', [InvestigacionesController::class, 'consultaValidacionInvestigacion'])->name('consultavalidacioninvestigacion')->middleware(['auth']);
    //Cambio de comentario en ruta get wilmer
    
    
    /* Route::get('verinformepdf/{id}', [PDFController::class, 'verInformePdfFinal'])->name('verinformepdf')->middleware(['auth']); */

    Route::get('verinformepdf/{id}', [PDFController::class, 'verInformePdfFinal'])->name('verinformepdf')->middleware(['auth']);

    Route::get('verInformePdfFinalPreview/{id}', [PDFController::class, 'verInformePdfFinalPreview'])->name('verInformePdfFinalPreview')->middleware(['auth']);
    Route::get('verinformesoportesPreview/{id}', [PDFController::class, 'generarInformeInvestigacionSoportesPreview'])->name('verinformesoportesPreview')->middleware(['auth']);

    Route::get('revisioninvestigacion/{id}', [InvestigacionesController::class, 'revisioninvestigacion'])->name('revisioninvestigacion')->middleware(['auth']);

    Route::put('investigacionstep/{investigacion}', [InvestigacionesController::class, 'investigacionstep'])->name('investigacionstep')->middleware(['auth']);
    Route::resource('asignacion', InvestigacionAsignacionController::class)->names('asignacion')->middleware(['auth']);
    Route::resource('antecedentescausante', InvestigacionConsultasAntecedentesCausanteController::class)->names('antecedentescausante')->middleware(['auth']);
    Route::resource('antecedentesbeneficiario', InvestigacionConsultasAntecedentesBeneficiariosController::class)->names('antecedentesbeneficiario')->middleware(['auth']);
    Route::resource('trazabilidadactividad', TrazabilidadActividadesRealizadasController::class)->names('trazabilidadactividad')->middleware(['auth']);

    Route::resource('novedad', NovedadController::class)->names('novedad')->middleware(['auth']);

    Route::resource('entrevistasolicitante', InvestigacionEntrevistaSolicitanteController::class)->names('entrevistasolicitante')->middleware(['auth']);
    Route::resource('auxilioFunerario', InvestigacionAuxilioFunerarioController::class)->names('auxilioFunerario')->middleware(['auth']);
    Route::resource('gastosvivienda', InvestigacionGastosViviendaController::class)->names('gastosvivienda')->middleware(['auth']);
    Route::resource('laborCampo', InvestigacionLaborCampoController::class)->names('laborCampo')->middleware(['auth']);
    Route::resource('entrevistaFamiliares', InvestigacionEntrevistaFamiliaresController::class)->names('entrevistaFamiliares')->middleware(['auth']);
    Route::resource('escolaridadBeneficiario', InvestigacionEscolaridadController::class)->names('escolaridadBeneficiario')->middleware(['auth']);

    Route::resource('acreditacion', InvestigacionAcreditacionController::class)->names('acreditacion')->middleware(['auth']);

    Route::resource('diafestivo', ControlDiasFestivosController::class)->names('diafestivo')->middleware(['auth']);
    Route::resource('investigacionverificacion', InvestigacionVerificacionController::class)->names('investigacionverificacion')->middleware(['auth']);
    Route::resource('fraude', InvestigacionFraudeController::class)->names('fraude')->middleware(['auth']);
    Route::resource('centrocostos', CentroCostosController::class)->names('centrocostos')->middleware(['auth']);
    Route::resource('estudioauxiliar', InvestigacionEstudiosAuxiliaresController::class)->names('estudioauxiliar')->middleware(['auth']);
    Route::resource('investigacionregion', InvestigacionRegion::class)->names('investigacionregion')->middleware(['auth']);


    Route::resource('secciones', SeccionesController::class)->names('secciones')->middleware(['auth']);
    Route::resource('seccionesformulario', SeccionesFormularioController::class)->names('seccionesformulario')->middleware(['auth']);

    Route::post('cargarMasivoInvestigaciones', [ExcelController::class, 'cargarMasivoInvestigaciones'])->name('cargarMasivoInvestigaciones')->middleware(['auth']);

    Route::post('DocumentosAnexosStore', [DocumentosController::class, 'DocumentosAnexosStore'])->name('DocumentosAnexosStore')->middleware(['auth']);

    Route::post('cargarMasivoUsuarios', [ExcelController::class, 'cargarMasivoUsuarios'])->name('cargarMasivoUsuarios')->middleware(['auth']);
    Route::get('informeInvestigacionAdministrativaPdf/{id}', [PDFController::class, 'generarInformeInvestigacionPDF'])->name('informeInvestigacionAdministrativaPdf')->middleware(['auth']);
    Route::get('informeInvestigacionAdministrativaSoportePdf/{id}', [PDFController::class, 'generarInformeInvestigacionSoportesPDF'])->name('informeInvestigacionAdministrativaSoportePdf')->middleware(['auth']);

    Route::get('generarInformeTrazabilidadInvestigacion/{id}', [InformesController::class, 'generarTrazabilidadInvestigacion'])->name('generarInformeTrazabilidadInvestigacion')->middleware(['auth']);
    Route::get('generarInformeInvestigaciones', [InformesController::class, 'informeInvestigacionExcel'])->name('generarInformeInvestigaciones')->middleware(['auth']);
    Route::get('generarInformeInvestigacionesEstado/{id}', [InformesController::class, 'informeInvestigacionEstadoExcel'])->name('generarInformeInvestigacionesEstado')->middleware(['auth']);

    /* Route::get('generarInformeInvestigacionesCompleto/{estado?}', [InformesController::class, 'informeInvestigacionCompletoExcel'])->name('generarInformeInvestigacionesCompleto')->middleware(['auth']); */
    Route::get('generarInformeInvestigacionesFiltros', [InformesController::class, 'informeInvestigacionFiltroExcel'])->name('generarInformeInvestigacionesFiltros')->middleware(['auth']);
    //Reporte de investigaciones solo para operacion
    Route::get('generarInformeInvestigacionesFiltrosOperacion', [InformesController::class, 'informeInvestigacionFiltroExcelOperaciones'])->name('generarInformeInvestigacionesFiltrosOperacion')->middleware(['auth']);
    
    Route::get('generarInformeInvestigacionesFiltrosCreador', [InformesController::class, 'generarInformeInvestigacionesFiltrosCreador'])->name('generarInformeInvestigacionesFiltrosCreador')->middleware(['auth']);
    Route::get('informeInvestigacionFiltroAprobador', [InformesController::class, 'informeInvestigacionFiltroAprobador'])->name('informeInvestigacionFiltroAprobador')->middleware(['auth']);
    
    Route::get('validar', [InformesController::class, 'validar'])->name('validar')->middleware(['auth']);

    Route::post('eliminarSoporte', [DocumentosController::class, 'eliminarSoporte'])->name('eliminarSoporte')->middleware(['auth']);

   //Route::get('beneficiarios/{id}/edit', [InvestigacionesBeneficiarios::class, 'edit'])->name('beneficiarios.edit');
    Route::post('beneficiarios/{id}', [InvestigacionesBeneficiariosController::class, 'update'])->name('beneficiarios.update');

    Route::get('creacionCarpetaFinalizada/{id}', [InvestigacionesController::class, 'creacionCarpetaFinalizada']);
    // Ruta agregada por Wilmer Contreras.
    Route::get('/descargarErrores', [ExcelController::class, 'descargarErrores'])->name('descargarErrores');
    Route::post('/moverCarpetas', [CarpetasController::class, 'moverCarpetas'])->name('moverCarpetas');
    Route::get('/validarCarpetas', [CarpetasController::class, 'validarCarpetas'])->name('validarCarpetas');
    Route::get('/masivoinvestigaciones',[ExcelController::class,'mostrarMasivo'])->name('masivoinvestigaciones');
    
    Route::post('/pdf/upload-database', [PDFINVESTIGController::class, 'uploadToDatabase'])->name('pdf.upload_database');

    Route::get('/pdf', function () {
        return view('pdf.index');
    })->name('pdf.index');
    
    
    Route::post('/pdf/upload', [PDFINVESTIGController::class, 'upload'])->name('pdf.upload.handle');

	Route::get('/radicacion/', [InvestigacionesRadicacionController::class, 'filtros'])->name('filtros')->middleware(['auth']);
    Route::get('/pendienteradicacion/', [InvestigacionesRadicacionController::class, 'buscarInvestigacionesRadicacion'])->name('pendienteradicacion')->middleware(['auth']);
    Route::post('/descargarZIPRadicacion/', [InvestigacionesRadicacionController::class, 'descargarZIPRadicacion'])->name('descargarZIPRadicacion')->middleware(['auth']);
    Route::post('/actualizarRadicados/', [InvestigacionesRadicacionController::class, 'actualizarRadicados'])->name('actualizarRadicados')->middleware(['auth']);
	
	
    Route::get('/facturacion/', [InvestigacionesFacturacionController::class, 'filtros'])->name('filtros');
    Route::get('/pendientefacturacion/', [InvestigacionesFacturacionController::class, 'buscarInvestigacionesFacturacion'])->name('pendientefacturacion');
    Route::post('/descargarXLSFacturacion/', [InvestigacionesFacturacionController::class, 'descargarXLSFacturacion'])->name('descargarXLSFacturacion');
    Route::post('/actualizarFacturados/', [InvestigacionesFacturacionController::class, 'actualizarFacturados'])->name('actualizarFacturados');
	
	
	Route::post('/cargarMasivoFacturacion', [ExcelController::class, 'cargarMasivoFacturacion'])->name('cargarMasivoFacturacion')->middleware(['auth']);
    Route::get('/masivofacturacion',[ExcelController::class,'mostrarMasivoFacturacion'])->name('masivofacturacion')->middleware(['auth']);

    Route::get('/comision/', [InvestigacionesFacturacionController::class, 'filtrosComision'])->name('filtrosComision')->middleware(['auth']);
    Route::get('/pendientecomision/', [InvestigacionesFacturacionController::class, 'buscarInvestigacionescomision'])->name('pendientecomision')->middleware(['auth']);
    Route::post('/descargarXLScomision/', [InvestigacionesFacturacionController::class, 'descargarXLScomision'])->name('descargarXLScomision')->middleware(['auth']);
    Route::post('/descargarXLScomisionResumen/', [InvestigacionesFacturacionController::class, 'descargarXLScomisionResumen'])->name('descargarXLScomisionResumen')->middleware(['auth']);

    Route::post('/descargarZipInformes/', [InvestigacionesFacturacionController::class, 'descargarZIPInformesAprobados'])->name('descargarZipInformes')->middleware(['auth']);
    Route::post('/actualizarcomision/', [InvestigacionesFacturacionController::class, 'actualizarcomision'])->name('actualizarcomision')->middleware(['auth']);
    Route::post('/notificarcomisiones/', [InvestigacionesFacturacionController::class, 'notificarcomisiones'])->name('notificarcomisiones')->middleware(['auth']);
    Route::get('/actualizarDoble/{id}/{establece}', [InvestigacionesFacturacionController::class, 'actualizarDoble'])->name('actualizarDoble')->middleware(['auth']);
    Route::get('/actualizarTarifaExtendida/{id}/{establece}', [InvestigacionesFacturacionController::class, 'actualizarTarifaExtendida'])->name('actualizarTarifaExtendida')->middleware(['auth']);
    Route::get('/actualizarPorBeneficiario/{id}/{establece}', [InvestigacionesFacturacionController::class, 'actualizarPorBeneficiario'])->name('actualizarPorBeneficiario')->middleware(['auth']);
    Route::get('/actualizarAuxiliar/{id}/{establece}', [InvestigacionesFacturacionController::class, 'actualizarAuxiliar'])->name('actualizarAuxiliar')->middleware(['auth']);
  
   Route::get('/verinformeInvestigadorpdf/{id}/{periodo1}/{periodo2}', [PDFController::class, 'verinformeInvestigadorpdf'])->name('verinformeInvestigadorpdf')->middleware(['auth']);
    Route::get('/verinformeInvestigador/{id}', [InvestigacionesFacturacionController::class, 'verinformeInvestigador'])->name('verinformeInvestigador')->middleware(['auth']);
    Route::post('/aceptarinforme/', [InvestigacionesFacturacionController::class, 'aceptarinforme'])->name('aceptarinforme')->middleware(['auth']);
     Route::get('/verinformeInvestigadorpdf/{id}', [PDFController::class, 'verinformeInvestigadorpdf2'])->name('verinformeInvestigadorpdf2')->middleware(['auth']);
    
	
    Route::get('/verinformeInvestigadorpdf/{id}/{periodo1}/{periodo2}', [PDFController::class, 'verinformeInvestigadorpdf'])->name('verinformeInvestigadorpdf')->middleware(['auth']);
    Route::get('/verinformeInvestigador/{id}', [InvestigacionesFacturacionController::class, 'verinformeInvestigador'])->name('verinformeInvestigador')->middleware(['auth']);
    Route::post('/aceptarinforme/', [InvestigacionesFacturacionController::class, 'aceptarinforme'])->name('aceptarinforme')->middleware(['auth']);
    Route::get('/verinformeInvestigadorpdf/{id}', [PDFController::class, 'verinformeInvestigadorpdf2'])->name('verinformeInvestigadorpdf2')->middleware(['auth']);
    

    Route::get('/notificaciones/', [NotificacionesController::class, 'Listado'])->name('Listado')->middleware(['auth']);
    Route::get('/Leido/{id}', [NotificacionesController::class, 'Leido'])->name('Leido')->middleware(['auth']);
    Route::get('/Cumplido/{id}', [NotificacionesController::class, 'Cumplido'])->name('Cumplido')->middleware(['auth']);
    Route::get('/LeidoTodo/', [NotificacionesController::class, 'LeidoTodo'])->name('LeidoTodo')->middleware(['auth']);
    Route::get('/CumplidoTodo/', [NotificacionesController::class, 'CumplidoTodo'])->name('CumplidoTodo')->middleware(['auth']);
      



    Route::get('/generarDocumentacion', function () {
        return view('generarDocumentacion.generarDocumentacion');
    })->name('mostrarVista')->middleware(['auth']);;
    
    Route::prefix('documentacion')->group(function () {
        Route::post('/procesar-documentacion', [generarDocumentacion::class, 'generarDocumentacion'])->name('documentacion.generarDocumentacion');
        Route::get('/documentacion/descargarErrores', [generarDocumentacion::class, 'descargarErrores'])->name('documentacion.descargarErrores');

        Route::get('/procesar-documentacion', [generarDocumentacion::class, 'generarDocumentacion'])->name('documentacion.generarDocumentacion');
        Route::get('/descargar-excel1', function () {
            return response()->download(storage_path('app/temp/investigaciones.xlsx'))->deleteFileAfterSend(true);
        })->name('documentacion.descargarExcel1');
        Route::get('/exportar-finalizadasHoy', [generarDocumentacion::class, 'exportarFinalizadasHoy'])->name('documentacion.descargarHoy');
        Route::post('/upload-file', [generarDocumentacion::class, 'uploadFile'])->name('documentacion.uploadFile');
        Route::post('/upload-file1', [generarDocumentacion::class, 'secondUploadFile'])->name('documentacion.secondUploadFile');
        
        Route::get('/exportar/{tipo}', [generarDocumentacion::class, 'exportarFinalizadas'])->name('documentacion.exportarExcel');
        Route::get('/progress/{jobId}', [generarDocumentacion::class, 'getProgress'])->name('documentacion.getProgress');
        

        
    });

     
    // Route::get('asignacion-masiva', [AsignacionMasivaAnalistasController::class, 'index'])->name('asignacion.masiva.index')->middleware(['auth']);
    Route::get('asignacion-masiva', [AsignacionMasivaAnalistasController::class, 'index'])
    ->name('asignacion.masiva.index')
    ->middleware(['auth', 'root']);

    Route::post('asignacion-masiva/import', [AsignacionMasivaAnalistasController::class, 'import'])->name('asignacion.masiva.import')->middleware(['auth']);

});
Route::get('edituser', [UserController::class, 'editProfile'])->name('edituser')->middleware(['auth']);
Route::put('userEditProfile', [UserController::class, 'updateProfile'])->name('userEditProfile')->middleware(['auth']);
