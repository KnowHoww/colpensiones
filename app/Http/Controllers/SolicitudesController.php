<?php

namespace App\Http\Controllers;

use App\Models\Solicitudes;
use Illuminate\Http\Request;

class SolicitudesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'NumeroRadicacionCaso' => 'required|string|max:50',
            'IdCase' => 'required|integer',
            'TipoInvestigacion' => 'required|string|max:2',
            'TipoRiesgo' => 'required|string|max:4',
            'DetalleRiesgo' => 'required|string|max:4',
            'TipoDocumento' => 'required|string|max:2',
            'NumeroDeDocumento' => 'required|integer',
            'PrimerNombre' => 'required|string|max:50',
            'SegundoNombre' => 'nullable|string|max:50',
            'PrimerApellido' => 'required|string|max:50',
            'SegundoApellido' => 'nullable|string|max:50',
            'RadicadoAsociado' => 'required|string|max:50',
            'Solicitud' => 'required|string|max:200',
            'Documentos' => 'required|array',
            'Documentos.*.Nombre' => 'required|string|max:1000',
            'Documentos.*.CodigoDocumental' => 'required|string|max:50',
        ]);

        if($caso = Solicitudes::create($request->all()))
        {
            return response()->json(['message' => 'Caso creado con éxito', 'data' => $caso], 201);
        }
        else{
            return response()->json(['message' => 'Caso no creado con éxito', 'data' => $caso], 400);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Solicitudes $solicitudes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Solicitudes $solicitudes)
    {
        //
    }
}
