<?php

namespace App\Http\Controllers;

use App\Models\Secciones;
use App\Models\SeccionesFormulario;
use App\Models\Servicios;
use App\Models\TipoInvestigacion;
use Illuminate\Http\Request;

class SeccionesFormularioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicios::all();
        return view('seccionesFormularios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SeccionesFormulario $seccionesFormulario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $codigo = TipoInvestigacion::find($id);
        $seccionesFormulario = SeccionesFormulario::where('investigacion', $codigo->codigo)->get();
        $secciones = Secciones::all();
        return view('seccionesFormularios.edit', compact('secciones', 'seccionesFormulario', 'codigo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ids = $request->input('secciones');
        SeccionesFormulario::where('Investigacion', $id)->delete();
        $secciones = $request->input('secciones');

        // Guarda las secciones seleccionadas en la base de datos
        foreach ($secciones as $seccion) {
            SeccionesFormulario::create([
                'Seccion' => $seccion,
                'Investigacion' => $id,
            ]);
        }
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeccionesFormulario $seccionesFormulario)
    {
        //
    }
}
