<?php

namespace App\Http\Controllers;

use App\Models\Servicios;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicios = Servicios::all();
        return view('servicios.index', compact('servicios'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('servicios.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $servicios = Servicios::create([
            'nombre' => ($request->nombre),
            'codigo' => ($request->codigo),
            'TiempoEntrega' => ($request->TiempoEntrega),
        ]);
        
        return redirect()->route('servicios.index')->with('info','Permiso creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Servicios $servicios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($servicio)
    {
        $servicio = Servicios::find($servicio);
        return view('servicios.edit', compact('servicio'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $servicio)
    {
        $servicios = Servicios::find($servicio);
        $servicios->update($request->all());
        return redirect()->route('servicios.index')->with('info', 'Servicio editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servicios $servicios)
    {
        //
    }
}
