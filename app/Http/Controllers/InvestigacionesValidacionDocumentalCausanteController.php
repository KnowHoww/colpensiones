<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionesValidacionDocumental;
use App\Models\InvestigacionesValidacionDocumentalCausante;
use Illuminate\Http\Request;

class InvestigacionesValidacionDocumentalCausanteController extends Controller
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
    public function show(InvestigacionesValidacionDocumentalCausante $InvestigacionesValidacionDocumentalCausante)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionesValidacionDocumentalCausante $InvestigacionesValidacionDocumentalCausante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validacionDocumentalCausante = InvestigacionesValidacionDocumentalCausante::find($id);
        $validacionDocumentalCausante->update($request->all());
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigacionesValidacionDocumentalCausante $InvestigacionesValidacionDocumentalCausante)
    {
        //
    }
}
