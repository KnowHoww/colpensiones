<?php

namespace App\Http\Controllers;

use App\Models\ControlDiasFestivos;
use Illuminate\Http\Request;

class ControlDiasFestivosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $diasFestivos = ControlDiasFestivos::all();
        return view('diasFestivos.index', compact('diasFestivos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('diasFestivos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        ControlDiasFestivos::create([
            'fecha' => $request->fecha,
            'observacion' => $request->observacion,
        ]);
        return back()->with('info', 'Información actualizada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ControlDiasFestivos $controlDiasFestivos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ControlDiasFestivos $controlDiasFestivos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ControlDiasFestivos $controlDiasFestivos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $data = ControlDiasFestivos::find($id);
        $data->delete();
        return back()->with('info', 'Información eliminada correctamente.');
    }
}
