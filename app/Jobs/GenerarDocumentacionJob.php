<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use App\Models\Investigaciones;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Http\Controllers\InvestigacionesController;
use App\Http\Controllers\PDFController;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ValidatingDataImport;
use App\Exports\generarDocumentacion as generarDocumentacionExport;
use App\Exports\generarDocumentacionNomina as generarDocumentacionNominaExport;
use App\Exports\generarDocumentacionReconocimiento as generarDocumentacionReconocimientoExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class GenerarDocumentacionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ids;
    protected $jobId;
    protected $tiposTramite;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($ids, $tiposTramite, $jobId)
    {
        $this->ids = $ids;
        $this->tiposTramite = $tiposTramite;
        $this->jobId = $jobId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $investigacionesController = app(InvestigacionesController::class);
        $pdfController = app(PDFController::class);

        $estado = 7; // EstadoFinalizado
        $nombreCarpetaFinalizado = "INVESTIGACIONES_FINALIZADAS_" . Carbon::now()->format('Y-m-d');
        $nombreCarpetaObjetado = "INVESTIGACIONES_FINALIZADAS_OBJETADAS_" . Carbon::now()->format('Y-m-d');
        $guardarEnDB = TRUE;
        $idsFinalizadasReconocimiento = [];
        $idsFinalizadasNomina = [];
        $idsObjetadasReconocimiento = [];
        $idsObjetadasNomina = [];
        $idSinTipoTramite = [];
        $nombreCarpetaCargueInforme = [];
        $pesoRadicacion = 0;
        $pesoRadicacionObjetados = 0;
        $pesoRadicacionFinalizados =0;
        $contador = "";
        $contadorObjetados = "";
        $contadorFinalizados = "";
        $contadorSoportes =0;
        $complementoFinalizada = "";
        $complementoObjetada ="";
        

        $totalSteps = count($this->ids);
        $step = 0;
        // $nombreBase = "investigaciones/DocumentacionGenerada/Radicacion_". Carbon::now()->format('Y-m-d_H-i-s') ."/";
        $nombreBase = "investigaciones" . DIRECTORY_SEPARATOR . "DocumentacionGenerada" . DIRECTORY_SEPARATOR . "Radicacion_" .Carbon::now()->format('Y-m-d_H-i-s'). DIRECTORY_SEPARATOR;

        $nombreCarpetaFinalizadas = $nombreBase . $nombreCarpetaFinalizado .DIRECTORY_SEPARATOR;
        $nombreCarpetaObjetada = $nombreBase. $nombreCarpetaObjetado . DIRECTORY_SEPARATOR;
                
        if (Storage::exists($nombreCarpetaFinalizadas)) {
            Storage::deleteDirectory($nombreCarpetaFinalizadas);
            Storage::deleteDirectory($nombreCarpetaObjetada);
        }

        Cache::put("progress_{$this->jobId}", 20);

        foreach ($this->ids as $index => $id) {
            // Log::info("Procesando ID: {$id}");
            $investigacion = Investigaciones::find($id);
            if ($investigacion) {
                $nombreCarpeta = ($investigacion->estado == 7) 
                    ? $nombreBase . $nombreCarpetaFinalizado . $complementoFinalizada .$contadorFinalizados.DIRECTORY_SEPARATOR 
                    : $nombreBase. $nombreCarpetaObjetado . $complementoObjetada. $contadorObjetados. DIRECTORY_SEPARATOR;
                
                if (!Storage::exists($nombreCarpeta)) {
                    Storage::makeDirectory($nombreCarpeta);
                }
                

                
                $pdfController->generarInformeInvestigacionPDF3($id, $estado, $nombreCarpeta, $guardarEnDB);
                $investigacionesController->creacionCarpetaFinalizada3($id, $estado, $nombreCarpeta, $guardarEnDB);
                // $contadorSoportes = $investigacionesController->creacionCarpetaFinalizada2($id, $estado, $nombreCarpeta, $guardarEnDB);
                // $pdfController->generarInformeInvestigacionSoportesPDF2($id, $nombreCarpeta, $guardarEnDB, $contadorSoportes);
                
                if ($investigacion->estado == 7) {

                    $nombreCarpetaCargueInforme[$id] =  $nombreCarpetaFinalizado . $complementoFinalizada. $contadorFinalizados;
                    
                    if ($this->tiposTramite[$index] == 22) {
                        $idsFinalizadasReconocimiento[] = $id;
                    } elseif ($this->tiposTramite[$index] == 23) {
                        $idsFinalizadasNomina[] = $id;
                    } else {
                        $idSinTipoTramite[] = $id;
                    }

                    $pesoDocumentosFinalizados = DB::table('investigaciones_documentos')
                                    ->where('idInvestigacion', $id)
                                    ->sum('peso');

                    $pesoRadicacionFinalizados +=  $pesoDocumentosFinalizados;

                    if($pesoRadicacionFinalizados > 419430400000 ){
                        
                        
                        
                        

                        if ($contadorFinalizados == "") {
                            
                            $nombreCarpeta = $nombreBase . $nombreCarpetaFinalizado . $complementoFinalizada .$contadorFinalizados.DIRECTORY_SEPARATOR ;
                            $oldPath = $nombreCarpeta;

                            $nombreCarpeta1 = $nombreCarpetaFinalizado . $complementoFinalizada .$contadorFinalizados;

                            $contadorFinalizados = 1;
                            $complementoFinalizada = "_";
                            foreach ($nombreCarpetaCargueInforme as $i => $carpeta) {
                                
                                if ($carpeta == $nombreCarpeta1) {
                                    $nombreCarpetaCargueInforme[$i] = $nombreCarpetaFinalizado . $complementoFinalizada . $contadorFinalizados ;
                                }
                            }

                            
                            $newPath = $nombreBase . $nombreCarpetaFinalizado . $complementoFinalizada .$contadorFinalizados.DIRECTORY_SEPARATOR;
                            
                            if (File::exists(Storage::path($oldPath))) {
                                Storage::move($oldPath, $newPath);
                            }

                            Excel::store(new generarDocumentacionReconocimientoExport($idsFinalizadasReconocimiento, true), $nombreBase . 'investigacionesFinalizadasReconocimiento' .$complementoFinalizada. $contadorFinalizados . '.xlsx');
                            Excel::store(new generarDocumentacionNominaExport($idsFinalizadasNomina, true), $nombreBase . 'investigacionesFinalizadasNomina' . $complementoFinalizada. $contadorFinalizados . '.xlsx');
                        
                            $contadorFinalizados = 2;
                            
                        } else {
                            Excel::store(new generarDocumentacionReconocimientoExport($idsFinalizadasReconocimiento, true), $nombreBase . 'investigacionesFinalizadasReconocimiento' .$complementoFinalizada. $contadorFinalizados . '.xlsx');
                            Excel::store(new generarDocumentacionNominaExport($idsFinalizadasNomina, true), $nombreBase . 'investigacionesFinalizadasNomina' . $complementoFinalizada. $contadorFinalizados . '.xlsx');
                        
                            $contadorFinalizados++;
                        }
                        $idsFinalizadasReconocimiento = [];
                        $idsFinalizadasNomina = [];
                        $pesoRadicacionFinalizados = 0;


                    }

                    

                } else {

                    $nombreCarpetaCargueInforme[$id] =  $nombreCarpetaObjetado . $complementoObjetada. $contadorObjetados;
                    
                    
                    if ($this->tiposTramite[$index] == 22) {
                        $idsObjetadasReconocimiento[] = $id;
                    } elseif ($this->tiposTramite[$index] == 23) {
                        $idsObjetadasNomina[] = $id;
                    } else {
                        $idSinTipoTramite[] = $id;
                    }

                    $pesoDocumentosObjetados = DB::table('investigaciones_documentos')
                                    ->where('idInvestigacion', $id)
                                    ->sum('peso');
                    
                    $pesoRadicacionObjetados += $pesoDocumentosObjetados;

                    if($pesoRadicacionObjetados > 419430400000) {

                        if ($contadorObjetados == "") {
                            $nombreCarpeta = $nombreBase. $nombreCarpetaObjetado . $complementoObjetada. $contadorObjetados. DIRECTORY_SEPARATOR;
                            $oldPath =$nombreCarpeta;
                            
                            $nombreCarpeta1 = $nombreCarpetaFinalizado . $complementoFinalizada .$contadorFinalizados;

                            $contadorObjetados = 1;
                            $complementoObjetada ="_";

                            foreach ($nombreCarpetaCargueInforme as $i => $carpeta) {
                                
                                if ($carpeta == $nombreCarpeta1) {
                                    $nombreCarpetaCargueInforme[$i] =  $nombreCarpetaObjetado . $complementoObjetada. $contadorObjetados;
                    
                                }
                            }

                            $nombreCarpeta = $nombreBase. $nombreCarpetaObjetado . $complementoObjetada. $contadorObjetados. DIRECTORY_SEPARATOR;
                            $newPath = $nombreCarpeta;
                            
                            if (File::exists(Storage::path($oldPath))) {
                                Storage::move($oldPath, $newPath);
                            }
                            Excel::store(new generarDocumentacionReconocimientoExport($idsObjetadasReconocimiento, true), $nombreBase . 'investigacionesObjetadasReconocimiento' .$complementoObjetada.  $contadorObjetados . '.xlsx');
                            Excel::store(new generarDocumentacionNominaExport($idsObjetadasNomina, true), $nombreBase . 'investigacionesObjetadasNomina' . $complementoObjetada.  $contadorObjetados . '.xlsx');
                        

                            $contadorObjetados = 2;


                        } else {
                            Excel::store(new generarDocumentacionReconocimientoExport($idsObjetadasReconocimiento, true), $nombreBase . 'investigacionesObjetadasReconocimiento' .$complementoObjetada.  $contadorObjetados . '.xlsx');
                            Excel::store(new generarDocumentacionNominaExport($idsObjetadasNomina, true), $nombreBase . 'investigacionesObjetadasNomina' . $complementoObjetada.  $contadorObjetados . '.xlsx');
                        
                            $contadorObjetados++;
                        }

                        $idsObjetadasReconocimiento = [];
                        $idsObjetadasNomina = [];
                        $pesoRadicacionObjetados = 0;
                    }

                    
                }
                
                
                try {
                    DB::beginTransaction();
                
                    DB::table('investigaciones_estados_radicacion')->insert([
                        'idInvestigacion' => $investigacion->id,
                        'idUsuario' => $investigacion->analista ?? '',  // Asignar cadena vacía si es NULL
                        'idEstadoRadicacion' => 1,
                        'observacion' => 'Se inicia Radicación',
                        'created_at' => now()->format("d/m/y"),  // Usar formato d/m/y
                    ]);
                
                    DB::commit();  // Confirmar la transacción si todo sale bien
                } catch (\Exception $e) {
                    DB::rollBack();  // Revertir la transacción en caso de error
                    Log::error('Error al insertar en investigaciones_estados_radicacion: ' . $e->getMessage());
                    
                }
                
                
                // Log::info("Finalizado ID: {$id}");
            }
            // Actualizar el progreso en el caché
            $step++;
            $progress = intval(($step / $totalSteps) * 100);
            Cache::put("progress_{$this->jobId}", $progress);
            // Log::info("Progreso: {$progress}%");
            
            //Log::info("Progress for job {$this->jobId}: {$progress}%");
        }

        // Guardar los archivos Excel
        // Guardar los archivos Excel
        Excel::store(new generarDocumentacionExport($this->ids, $nombreCarpetaCargueInforme), $nombreBase . 'investigacionesResumen'  . '.xlsx');
        
        Excel::store(new generarDocumentacionReconocimientoExport($idsFinalizadasReconocimiento, true), $nombreBase . 'investigacionesFinalizadasReconocimiento' . $complementoFinalizada . $contadorFinalizados . '.xlsx');
        Excel::store(new generarDocumentacionNominaExport($idsFinalizadasNomina, true), $nombreBase . 'investigacionesFinalizadasNomina' . $complementoFinalizada . $contadorFinalizados . '.xlsx');
        Excel::store(new generarDocumentacionReconocimientoExport($idsObjetadasReconocimiento, true), $nombreBase . 'investigacionesObjetadasReconocimiento' . $complementoObjetada .  $contadorObjetados . '.xlsx');
        Excel::store(new generarDocumentacionNominaExport($idsObjetadasNomina, true), $nombreBase . 'investigacionesObjetadasNomina' . $complementoObjetada .  $contadorObjetados . '.xlsx');
            
        Excel::store(new generarDocumentacionExport($this->ids, $nombreCarpetaCargueInforme), 'temp/investigaciones' .  '.xlsx');

        // Marcar la tarea como completada
        Cache::put("progress_{$this->jobId}", 100);
    }
}
