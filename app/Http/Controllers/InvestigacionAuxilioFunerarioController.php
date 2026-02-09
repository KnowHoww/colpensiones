<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionAuxilioFunerario;
use Illuminate\Http\Request;

class InvestigacionAuxilioFunerarioController extends Controller
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
    public function show(InvestigacionAuxilioFunerario $investigacionAuxilioFunerario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionAuxilioFunerario $investigacionAuxilioFunerario)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $auxilioFunerario = InvestigacionAuxilioFunerario::find($id);
        $auxilioFunerario->update($request->all());
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigacionAuxilioFunerario $investigacionAuxilioFunerario)
    {
        //
    }
}
