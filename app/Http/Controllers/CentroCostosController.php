<?php

namespace App\Http\Controllers;

use App\Models\CentroCostos;
use Illuminate\Http\Request;

class CentroCostosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $centrosCosto = CentroCostos::all();
        return view('centroCostos.index', compact('centrosCosto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('centroCostos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $centroCostos = CentroCostos::create([
            'nombre' => ($request->nombre),
            'codigo' => ($request->codigo),
        ]);

        return redirect()->route('centrocostos.index')->with('info', 'Centro de costo creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $centroCosto = CentroCostos::find($id);
        return view('centroCostos.edit', compact('centroCosto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $centroCostos = CentroCostos::find($id);
        $centroCostos->update($request->all());
        return redirect()->route('centrocostos.index')->with('info', 'Centro de costo editado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}