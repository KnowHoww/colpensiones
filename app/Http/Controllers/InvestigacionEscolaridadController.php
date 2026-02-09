<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionEscolaridad;
use Illuminate\Http\Request;

class InvestigacionEscolaridadController extends Controller
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InvestigacionEscolaridad $investigacionEscolaridad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionEscolaridad $investigacionEscolaridad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $escolaridad = InvestigacionEscolaridad::find($id);
        $escolaridad->update($request->all());
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigacionEscolaridad $investigacionEscolaridad)
    {
        //
    }
}
