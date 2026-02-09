<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionEntrevistaSolicitante;
use Illuminate\Http\Request;

class InvestigacionEntrevistaSolicitanteController extends Controller
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
    public function show(InvestigacionEntrevistaSolicitante $investigacionTrabajoRealizado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionEntrevistaSolicitante $investigacionTrabajoRealizado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $entrevistaSolicitante = InvestigacionEntrevistaSolicitante::find($id);
        $entrevistaSolicitante->update($request->all());
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigacionEntrevistaSolicitante $investigacionTrabajoRealizado)
    {
        //
    }
}
