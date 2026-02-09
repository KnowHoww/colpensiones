<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Investigaciones;
use File;

class CarpetasController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $tempPath = $file->storeAs('public/uploads', $filename); // Almacenar temporalmente en el disco público

        // Asumimos que `nombreCarpeta` está relacionado con el `NumeroRadicacionCaso` en alguna forma.
        $numeroRadicadoCaso = pathinfo($filename, PATHINFO_FILENAME);
        $investigacion = Investigaciones::where('NumeroRadicacionCaso', $numeroRadicadoCaso)->first();

        if ($investigacion) {
            $nuevoNombreCarpeta = $investigacion->nombreCarpeta;
            $nuevoDirectorio = "investigaciones/radicado/{$nuevoNombreCarpeta}";

            // Crear la carpeta si no existe
            if (!Storage::exists($nuevoDirectorio)) {
                Storage::makeDirectory($nuevoDirectorio);
            }

            // Mover el archivo a la nueva carpeta
            Storage::move($tempPath, "{$nuevoDirectorio}/{$filename}");
        } else {
            return response()->json(['error' => 'Investigación no encontrada'], 404);
        }

        return response()->json(['success' => 'Archivo subido y movido correctamente']);
    }

    // public function moverCarpetas()
    // {
    //     // Recupera los nuevos IDs y duplicados almacenados en la sesión.
    //     $idNormal = session('idNormal', []);
    //     $idDuplicado = session('idDuplicado', []);
    //     $duplicados = session('duplicados', []);
        
    //     // Verifica si hay IDs nuevos o duplicados en la sesión.
    //     if (empty($idNormal) && empty($idDuplicado)) {
    //         return redirect()->back()->with('error', 'No hay nuevas investigaciones para procesar.');
    //     }
        
    //     $carpetasMovidas = false;
    
    //     // Mueve las investigaciones normales
    //     $investigacionesNormales = Investigaciones::whereIn('id', $idNormal)->get();
    //     foreach ($investigacionesNormales as $investigacion) {
    //         $nombreCarpeta = $investigacion->NumeroRadicacionCaso;
    //         $directorioActual = "investigaciones/masivo/{$nombreCarpeta}";
    //         $nuevoDirectorio = "investigaciones/radicado/{$investigacion->nombreCarpeta}";
    
    //         if (Storage::exists($directorioActual)) {
    //             if (!Storage::exists($nuevoDirectorio)) {
    //                 Storage::makeDirectory($nuevoDirectorio);
    //             }
                
    //             $archivos = Storage::files($directorioActual);
    //             foreach ($archivos as $archivo) {
    //                 $nombreArchivo = basename($archivo);
    //                 Storage::move($archivo, "{$nuevoDirectorio}/{$nombreArchivo}");
    //             }
    //             Storage::deleteDirectory($directorioActual);
    //             $carpetasMovidas = true;
    //         }
    //     }
    
    //     // Mueve las investigaciones duplicadas
    //     // Recupera las investigaciones duplicadas utilizando los IDs almacenados en idDuplicado.
    //     // Para cada investigación duplicada, encuentra la información de duplicado correspondiente en duplicados.
    //     // Extrae el índice de duplicado del valor de duplicado.
    //     // Define las rutas del directorio actual (en masivo/duplicadas) y del nuevo directorio utilizando NumeroRadicacionCaso y nombreCarpeta.
        

    //     // Mueve las investigaciones duplicadas
    //     $investigacionesDuplicadas = Investigaciones::whereIn('id', $idDuplicado)->get();
    //     foreach ($investigacionesDuplicadas as $investigacion) {
    //         $duplicadoInfo = array_filter($duplicados, function($d) use ($investigacion) {
    //             return $d['numeroRadicacionCaso'] === $investigacion->NumeroRadicacionCaso;
    //         });

    //         foreach ($duplicadoInfo as $duplicado) {
    //             $indiceDuplicado = preg_replace('/[^0-9]/', '', $duplicado['duplicado']);
    //             $directorioActual = "investigaciones/masivo/duplicadas/{$investigacion->NumeroRadicacionCaso}{$indiceDuplicado}";
    //             $nuevoDirectorio = "investigaciones/radicado/{$investigacion->nombreCarpeta}";

    //             if (Storage::exists($directorioActual)) {
    //                 if (!Storage::exists($nuevoDirectorio)) {
    //                     Storage::makeDirectory($nuevoDirectorio);
    //                 }

    //                 $archivos = Storage::files($directorioActual);
    //                 foreach ($archivos as $archivo) {
    //                     $nombreArchivo = basename($archivo);
    //                     Storage::move($archivo, "{$nuevoDirectorio}/{$nombreArchivo}");
    //                 }
    //                 Storage::deleteDirectory($directorioActual);
    //                 $carpetasMovidas = true;
    //             }
    //         }
    //     }
    
    //     if ($carpetasMovidas) {
    //         return redirect()->back()->with('success', 'Carpetas movidas y renombradas correctamente');
    //     } else {
    //         return redirect()->back()->with('error', 'No hay carpetas para mover');
    //     }
    // }
    
    public function moverCarpetas()
    {
        // Recupera los nuevos IDs y duplicados almacenados en la sesión.
        $idNormal = session('idNormal', []);
        $idDuplicado = session('idDuplicado', []);
        $duplicados = session('duplicados', []);
        

        // Verifica si hay IDs nuevos o duplicados en la sesión.
        if (empty($idNormal) && empty($idDuplicado)) {
            return redirect()->back()->with('error', 'No hay nuevas investigaciones para procesar.');
        }
        
        $carpetasMovidas = false;

        // Función para mover directorios completos
        function moverDirectorioCompleto($directorioActual, $nuevoDirectorio) {
            if (Storage::exists($directorioActual)) {
                if (!Storage::exists($nuevoDirectorio)) {
                    Storage::makeDirectory($nuevoDirectorio);
                }
                
                $archivos = Storage::allFiles($directorioActual);
                $subdirectorios = Storage::allDirectories($directorioActual);

                // Mover todos los archivos
                foreach ($archivos as $archivo) {
                    $nombreArchivo = str_replace($directorioActual, $nuevoDirectorio, $archivo);
                    Storage::move($archivo, $nombreArchivo);
                }

                // Mover todos los subdirectorios y su contenido
                foreach ($subdirectorios as $subdirectorio) {
                    $nuevoSubdirectorio = str_replace($directorioActual, $nuevoDirectorio, $subdirectorio);
                    moverDirectorioCompleto($subdirectorio, $nuevoSubdirectorio);
                }

                // Eliminar el directorio actual una vez que todo su contenido ha sido movido
                Storage::deleteDirectory($directorioActual);
                return true;
            }
            return false;
        }

        // Mueve las investigaciones normales
        // Mueve las investigaciones normales
        $viejoNombreCarpeta = session('viejoNombreCarpeta', []);
        $i = 0;

        $investigacionesNormales = Investigaciones::whereIn('id', $idNormal)->get();

        $viejoNombreCarpeta = session('viejoNombreCarpeta', []);
        $i = 0;
        foreach ($investigacionesNormales as $investigacion) {

            $nombreCarpeta =  $viejoNombreCarpeta[$i]; 

            $nuevoNombreCarpeta = $investigacion->NumeroRadicacionCaso . "_" . $investigacion->id;
            $directorioActual = "investigaciones/masivo/{$nombreCarpeta}";
            $nuevoDirectorio = "investigaciones/radicado/{$nuevoNombreCarpeta}";

            if (moverDirectorioCompleto($directorioActual, $nuevoDirectorio)) {
                $carpetasMovidas = true;
            }
            $i++;
        }

        // // Mueve las investigaciones duplicadas
        // foreach ($duplicados as $duplicado) {
        //     $investigacion = Investigaciones::find($duplicado['id']);
        //     $indiceDuplicado = preg_replace('/[^0-9]/', '', $duplicado['duplicado']);
        //     $nuevoNombreCarpeta = $investigacion->NumeroRadicacionCaso . "_" . $investigacion->id;
        //     $directorioActual = "investigaciones/masivo/duplicadas/{$investigacion->nombreCarpeta}{$indiceDuplicado}";
        //     $nuevoDirectorio = "investigaciones/radicado/{$nuevoNombreCarpeta}";

        //     if (moverDirectorioCompleto($directorioActual, $nuevoDirectorio)) {
        //         $carpetasMovidas = true;
        //     }
        // }


        if ($carpetasMovidas) {
            return redirect()->back()->with('success', 'Carpetas movidas y renombradas correctamente');
        } else {
            return redirect()->back()->with('error', 'No hay carpetas para mover');
        }
    }



    public function validarCarpetas()
    {

        $nuevosIds = session('nuevosIds', []);
        
        $investigaciones = Investigaciones::whereIn('id', $nuevosIds)->get();
        $resultados = [];
        foreach ($investigaciones as $investigacion) {
            $nuevoNombreCarpeta = $investigacion->NumeroRadicacionCaso . "_" . $investigacion->id;
            $carpetaExiste = Storage::exists("investigaciones/radicado/{$nuevoNombreCarpeta}");
            $resultados[] = [
                'id' => $investigacion->id,
                'nombreCarpeta' => $nuevoNombreCarpeta,
                'existe' => $carpetaExiste
            ];
        }
        return response()->json($resultados);
    }
    
}
