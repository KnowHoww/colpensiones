<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionEntrevistaFamiliares;
use Illuminate\Http\Request;

class InvestigacionEntrevistaFamiliaresController extends Controller
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
    public function show(InvestigacionEntrevistaFamiliares $investigacionEntrevistaFamiliares)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionEntrevistaFamiliares $investigacionEntrevistaFamiliares)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $entrevistaFamiliares = InvestigacionEntrevistaFamiliares::find($id);
        $entrevistaFamiliares->update($request->all());
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigacionEntrevistaFamiliares $investigacionEntrevistaFamiliares)
    {
        //
    }
}
