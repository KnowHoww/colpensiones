<?php

namespace App\Http\Controllers;

use App\Models\InvestigacionesBeneficiarios;
use Illuminate\Http\Request;

class InvestigacionesBeneficiariosController extends Controller
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
    public function show(InvestigacionesBeneficiarios $investigacionesBeneficiarios)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InvestigacionesBeneficiarios $investigacionesBeneficiarios)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pais = InvestigacionesBeneficiarios::find($id);
        $pais->update($request->all());
        return back()->with('error', 'No se pudo eliminar el beneficiario.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function eliminarBeneficarioRevision(Request $request)
    {
        $beneficiario = InvestigacionesBeneficiarios::find($request->id);
        if ($beneficiario) {
            $beneficiario->delete();
            return redirect()->back()->with('info', 'Beneficiario eliminado correctamente.');
        }
        return redirect()->back()->with('error', 'No se pudo eliminar el beneficiario.');
    }
}
