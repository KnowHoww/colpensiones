<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionAsignacion;
use App\Models\Investigaciones;
use Illuminate\Http\Request;

class InvestigacionAsignacionController extends Controller
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
    public function show(InvestigacionAsignacion $investigacionAsignacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionAsignacion $investigacionAsignacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $investigacionAsignacion = InvestigacionAsignacion::find($id);
        $investigacionAsignacion->update($request->all());

        return back()->with('info', 'Asignación realizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigacionAsignacion $investigacionAsignacion)
    {
        //
    }
}
