<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Investigaciones;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\InvestigacionesController;
use App\Http\Controllers\PDFController;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ValidatingDataImport;
use Illuminate\Support\Facades\Log;

use App\Jobs\GenerarDocumentacionJob;

use App\Jobs\DocumentacionInformesJOB;
use App\Jobs\DocumentacionInformesSemanal;
use Illuminate\Support\Facades\Cache;

use App\Exports\generarDocumentacion as generarDocumentacionExport;
use App\Exports\generarDocumentacionNomina as generarDocumentacionNominaExport;
use App\Exports\exportarFinalizadasHoy as exportarFinalizadasHoyExport;


class generarDocumentacion extends Controller
{   
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        try {

            $path = $request->file('file')->storeAs('uploads', $request->file('file')->getClientOriginalName(), 'local');
            // Guarda la ruta del archivo en la sesión
            session(['uploaded_file_path' => $path]);

            // Importa y valida el archivo
            Excel::import(new ValidatingDataImport, storage_path("app/{$path}"));


            // if (session()->has('errors')) {
            //     // Excel::download(new exportarFinalizadasHoyExport(), 'investigacionesProcesadas.xlsx');
            //     return redirect()->route('mostrarVista')
            //         ->with('file_errors', session('errors'))
            //         ->with('trigger_excel_download', true);
            // }

            return redirect()->route('mostrarVista')->with('file_uploaded', 'Archivo cargado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('mostrarVista')->with('file_errors', ['Error al procesar el archivo.']);
        }
    }
   


    public function generarDocumentacion(Request $request)
    {
        set_time_limit(3600);
        try {
            // Obtén la ruta del archivo Excel desde la sesión
            $excelFilePath = session('uploaded_file_path');

            // Leer el archivo Excel usando Maatwebsite\Excel
            $data = Excel::toArray(new ValidatingDataImport, storage_path("app/{$excelFilePath}"))[0];

            // Leer los datos del archivo Excel
            $ids = [];
            $tiposTramite = [];
            $reconocimiento = FALSE;

            // Saltar la primera fila si tiene encabezados
            foreach ($data as $rowIndex => $row) {
                if ($rowIndex === 0) {
                    continue; // Saltar la cabecera
                }
                $ids[] = $row[0]; // Asumiendo que los IDs están en la primera columna
                $tiposTramite[] = $row[3];
            }

            session(['ids' => $ids]);

            $jobId = uniqid('job_', true);
            Cache::put("progress_{$jobId}", 0, 3600); // Inicializar el progreso a 0
            //GenerarDocumentacionJob::dispatch($ids, $tiposTramite, $jobId); // Despachar el job
            DocumentacionInformesJOB::dispatch($ids, $tiposTramite, $jobId); //Despachar el job para la generacion de la documentacion de solo los pdf
            //DocumentacionInformesSemanal::dispatch($ids, $tiposTramite, $jobId);
            // Cache::put("progress_{$jobId}", 100);
            return response()->json(['jobId' => $jobId, 'message' => 'Proceso completado. La descarga comenzará en breve.']);
            
        } catch (\Exception $e) {
            throw $e;
            // Asegurarse de capturar cualquier excepción y devolver un mensaje de error en JSON
            // return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function secondUploadFile(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

    }

    public function getProgress($jobId)
    {
        $progress = Cache::get("progress_{$jobId}", 0);
       
        Log::info("Progress for job {$jobId}: {$progress}%");
       
        return response()->json(['progress' => $progress]);
    }

     



    public function exportarFinalizadas($tipo)
    {
        $nombreBase = "investigaciones" . DIRECTORY_SEPARATOR . "DocumentacionGenerada" . DIRECTORY_SEPARATOR . "Radicacion_" .Carbon::now()->format('Y-m-d_H-i-s'). DIRECTORY_SEPARATOR . "Radicacion_". Carbon::now()->format('Y-m-d') . "/";

        // $nombreBase = "investigaciones/DocumentacionGenerada/Radicacion_". Carbon::now()->format('Y-m-d') . "/";
        switch ($tipo) {
            case 'finalizadas-reconocimiento':
                $filePath = $nombreBase. 'investigacionesFinalizadasReconocimiento.xlsx';
                break;
            case 'finalizadas-nomina':
                $filePath = $nombreBase. 'investigacionesFinalizadasNomina.xlsx';
                break;
            case 'objetadas-reconocimiento':
                $filePath = $nombreBase. 'investigacionesObjetadasReconocimiento.xlsx';
                break;
            case 'objetadas-nomina':
                $filePath = $nombreBase. 'investigacionesObjetadasNomina.xlsx';
                break;
            default:
                return abort(404, 'Tipo de exportación no encontrado');
        }

        return Storage::download($filePath);
    }

    public function exportarFinalizadasHoy(){
        return Excel::download(new exportarFinalizadasHoyExport(), 'investigacionesFinalizadasHoy.xlsx');
    }

    public function exportarExcel()
    {
        // Obtener los IDs de la sesión
        $ids = session('ids', []);

        if (empty($ids)) {
            return redirect()->route('mostrarVista')->with('error', 'No hay IDs disponibles para exportar.');
        }
  
        // Pasar los IDs al exportador

        return Excel::download(new generarDocumentacionExport($ids ), 'investigaciones.xlsx');
    }


    public function descargarErrores()
    {
        $ids = session('idsConsulta', []); 

        if (empty($ids)) {
            return redirect()->route('mostrarVista')->with('error', 'No hay IDs de error disponibles para exportar.');
        }
    
        return Excel::download(new exportarFinalizadasHoyExport($ids), 'investigacionesProcesadas.xlsx');
    
    }


}

    