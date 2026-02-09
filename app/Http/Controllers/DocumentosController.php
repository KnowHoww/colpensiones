<?php

namespace App\Http\Controllers;

use App\Models\Documentos;
use App\Models\Investigaciones;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class DocumentosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Storage::disk('radicados')->allDirectories();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    
    private function getGUID(){
        if (function_exists('com_create_guid')){
            return com_create_guid();
        }
        else {
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return $uuid;
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       if (request()->has('type') && request('type') =='firma')
        {
                
            $user = User::find($request->id);
            $nombreArchivo = '';
            if ($request->hasFile('firma')) {
                $file = $request->file('firma') ;
                $nombreArchivo = trim($this->getGUID(), '{}') . '.jpg';
                Storage::disk('firmas')->putFileAs('/' , $file, $nombreArchivo);
                $user->firma =  $nombreArchivo;
                $user->update(['firma' => $nombreArchivo ]);
                
            }       
            return back()->with('info', 'Documentos cargados correctamente.');
        }
        else {
            $investigacion = Investigaciones::find($request->id);
            $consecutivo = 1;
            foreach ($request->file('files') as $file) {
                $nombreArchivo = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/investigacion', $file, $nombreArchivo);
                $consecutivo++;
            }
        }
        return back()->with('info', 'Documentos cargados correctamente.');
    }

    public function DocumentosAnexosStore(Request $request)
    {
        //$investigacion = Investigaciones::where('NumeroRadicacionCaso', $request->id)->first();
        $investigacion = Investigaciones::find($request->id);
        $num_total = count(Storage::disk('investigaciones')->allFiles('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico'));
        $consecutivo = $num_total;

        if ($request->hasFile('inmuebles') && $request->file('inmuebles') !== null) {
            foreach ($request->file('inmuebles') as $file) {
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'inmueble_' . ++$num_total . '.' . $extension;
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico', $file, $nombreArchivo);
                $consecutivo++;
            }
        }

        if ($request->hasFile('servicios') && $request->file('servicios') !== null) {
            foreach ($request->file('servicios') as $file) {
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'servicios_' . ++$num_total . '.' . $extension;
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico', $file, $nombreArchivo);
                $consecutivo++;
            }
        }

        if ($request->hasFile('pertenencias') && $request->file('pertenencias') !== null) {
            foreach ($request->file('pertenencias') as $file) {
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'pertenencias_' . ++$num_total . '.' . $extension;
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico', $file, $nombreArchivo);
                $consecutivo++;
            }
        }

        if ($request->hasFile('clinica') && $request->file('clinica') !== null) {
            foreach ($request->file('clinica') as $file) {
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'clinica_' . ++$num_total . '.' . $extension;
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico', $file, $nombreArchivo);
                $consecutivo++;
            }
        }

        if ($request->hasFile('familiares') && $request->file('familiares') !== null) {
            foreach ($request->file('familiares') as $file) {
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'familiares_' . ++$num_total . '.' . $extension;
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico', $file, $nombreArchivo);
                $consecutivo++;
            }
        }

        if ($request->hasFile('investigador') && $request->file('investigador') !== null) {
            foreach ($request->file('investigador') as $file) {
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'investigador_' . ++$num_total . '.' . $extension;
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico', $file, $nombreArchivo);
                $consecutivo++;
            }
        }

        if ($request->hasFile('basesdedatos') && $request->file('basesdedatos') !== null) {
            foreach ($request->file('basesdedatos') as $file) {
                $extension = $file->getClientOriginalExtension();
                $nombreArchivo = 'basesdedatos_' . ++$num_total . '.' . $extension;
                Storage::disk('investigaciones')->putFileAs('/radicado/' . $investigacion->nombreCarpeta . '/soporteFotografico', $file, $nombreArchivo);
                $consecutivo++;
            }
        }
        return back()->with('info', 'Documentos cargados correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Documentos $documentos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Documentos $documentos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documentos $documentos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function eliminarSoporte(Request $request)
    {
        $rutaArchivo = $request->input('ruta_archivo');
        try {
            if (Storage::disk('investigaciones')->exists($rutaArchivo)) {
                $deleted = Storage::disk('investigaciones')->delete($rutaArchivo);

                if ($deleted) {
                    return redirect()->back()->with('success', 'El archivo ha sido eliminado correctamente');
                } else {
                    return redirect()->back()->with('success', 'El archivo no ha sido eliminado');
                }
            } else {
                return redirect()->back()->with('success', 'El archivo ha sido eliminado correctamente');
            }
        } catch (Exception $e) {
            // Registra el error y devuelve una respuesta adecuada
            Log::error('Error al eliminar el archivo: ' . $e->getMessage());
            return redirect()->back()->with('success', 'El archivo no ha sido eliminado correctamente');
        }

        // Redirigir o hacer cualquier otra cosa después de eliminar el archivo
        return redirect()->back()->with('success', 'El archivo ha sido eliminado correctamente');
    }
}
