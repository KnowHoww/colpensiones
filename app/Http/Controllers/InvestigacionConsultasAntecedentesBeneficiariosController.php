<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionConsultasAntecedentesBeneficiarios;
use Illuminate\Http\Request;

class InvestigacionConsultasAntecedentesBeneficiariosController extends Controller
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
    public function show(InvestigacionConsultasAntecedentesBeneficiarios $investigacionConsultasAntecedentesBeneficiarios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionConsultasAntecedentesBeneficiarios $investigacionConsultasAntecedentesBeneficiarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $antecedenteBeneficiario = InvestigacionConsultasAntecedentesBeneficiarios::find($id);
        $antecedenteBeneficiario->update($request->all());
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InvestigacionConsultasAntecedentesBeneficiarios $investigacionConsultasAntecedentesBeneficiarios)
    {
        //
    }
}
