<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

// Imports
use App\Imports\AsignacionAnalistasImport;
use App\Imports\AsignacionAnalistasCambioEstadoImport;
// Import NUEVO
use App\Imports\AsignacionCoordinadorInvestigadorImport;

// Export para errores
use App\Exports\ErroresImportacionExport;

class AsignacionMasivaAnalistasController extends Controller
{
    public function index()
    {
        // Vista con el formulario
        return view('asignacionmasivaanalistas.index');
    }

    public function import(Request $request)
    {
        // Validamos
        $request->validate([
            'tipo_archivo' => 'required|in:solo_asignacion,asignacion_cambio,asignacion_coordinador_investigador',
            'file'         => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $tipoArchivo = $request->input('tipo_archivo');
        $file        = $request->file('file');

        // Instanciar la clase de import según el tipo:
        if ($tipoArchivo === 'solo_asignacion') {
            $import = new AsignacionAnalistasImport;
        } elseif ($tipoArchivo === 'asignacion_cambio') {
            $import = new AsignacionAnalistasCambioEstadoImport;
        } else {
            $import = new AsignacionCoordinadorInvestigadorImport;
        }

        try {
            // Procesar import
            Excel::import($import, $file);

            // Obtener errores
            $errores = method_exists($import, 'getErrores') ? $import->getErrores() : [];

            if (count($errores) > 0) {
                // Generar un Excel de errores
                $nombreArchivo = 'errores_importacion_'.date('YmdHis').'.xlsx';
                return Excel::download(new ErroresImportacionExport($errores), $nombreArchivo);
            }

            // Sin errores
            return redirect()->back()->with('success', 'La importación se realizó correctamente (sin errores).');

        } catch (\Exception $e) {
            // Manejo de errores globales
            return redirect()->back()->with('error', 'Error al procesar el archivo: '.$e->getMessage());
        }
    }
}
