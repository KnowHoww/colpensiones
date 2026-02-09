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

use App\Exports\generarDocumentacionNominaReports as generarDocumentacionNominaReports;
use App\Exports\generarDocumentacionReconocimientoReports as generarDocumentacionReconocimientoReports;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class DocumentacionInformesJOB implements ShouldQueue
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
        $makeJustReports = TRUE;
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
        $nombreBase = "investigaciones" . DIRECTORY_SEPARATOR . "DocumentacionGenerada" . DIRECTORY_SEPARATOR . "Informes_" .Carbon::now()->format('Y-m-d_H-i-s'). DIRECTORY_SEPARATOR;

        $nombreCarpetaFinalizadas = $nombreBase . $nombreCarpetaFinalizado .DIRECTORY_SEPARATOR;
        $nombreCarpetaObjetada = $nombreBase. $nombreCarpetaObjetado . DIRECTORY_SEPARATOR;
                        

        foreach ($this->ids as $index => $id) {
            // Log::info("Procesando ID: {$id}");
            $investigacion = Investigaciones::find($id);
            if ($investigacion) {
                $nombreCarpeta = ($investigacion->estado == 7) 
                    ? $nombreBase . $nombreCarpetaFinalizado .DIRECTORY_SEPARATOR 
                    : $nombreBase. $nombreCarpetaObjetado . DIRECTORY_SEPARATOR;
                
                if (!Storage::exists($nombreCarpeta)) {
                    Storage::makeDirectory($nombreCarpeta);
                }
                

                
                $pdfController->generarInformeInvestigacionPDF3($id, $estado, $nombreCarpeta, $guardarEnDB, $makeJustReports);
                
                
                if ($investigacion->estado == 7) {

                    $nombreCarpetaCargueInforme[$id] =  $nombreCarpetaFinalizado . $complementoFinalizada. $contadorFinalizados;
                    if ($this->tiposTramite[$index] == 22) {
                        $idsFinalizadasReconocimiento[] = $id;
                    } elseif ($this->tiposTramite[$index] == 23) {
                        $idsFinalizadasNomina[] = $id;
                    } else {
                        $idSinTipoTramite[] = $id;
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
        
        Excel::store(new generarDocumentacionReconocimientoReports($idsFinalizadasReconocimiento, true), $nombreBase . 'investigacionesFinalizadasReconocimientoReports' . $complementoFinalizada . $contadorFinalizados . '.xlsx');
        Excel::store(new generarDocumentacionNominaReports($idsFinalizadasNomina, true), $nombreBase . 'investigacionesFinalizadasNominaReports' . $complementoFinalizada . $contadorFinalizados . '.xlsx');
        Excel::store(new generarDocumentacionReconocimientoReports($idsObjetadasReconocimiento, true), $nombreBase . 'investigacionesObjetadasReconocimientoReports' . $complementoObjetada .  $contadorObjetados . '.xlsx');
        Excel::store(new generarDocumentacionNominaReports($idsObjetadasNomina, true), $nombreBase . 'investigacionesObjetadasNominaReports' . $complementoObjetada .  $contadorObjetados . '.xlsx');
            
        Excel::store(new generarDocumentacionExport($this->ids, $nombreCarpetaCargueInforme), 'temp/investigaciones' .  '.xlsx');

        // Marcar la tarea como completada
        Cache::put("progress_{$this->jobId}", 100);
    }
}
