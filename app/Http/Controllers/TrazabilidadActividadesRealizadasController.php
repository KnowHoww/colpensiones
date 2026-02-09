<?php

namespace App\Http\Controllers;

use App\Models\TrazabilidadActividadesRealizadas;
use App\Models\Investigaciones;
use App\Models\investigacionesObservacionesEstado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrazabilidadActividadesRealizadasController extends Controller
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
        $actividadestipoinvestigacion = json_decode($request->input('actividadestipoinvestigacion'), true);

        $indice = $request->input('actividad');

        if (array_key_exists($indice, $actividadestipoinvestigacion)) {
            $actividadSeleccionada = $actividadestipoinvestigacion[$indice];
        } else {
            return redirect()->back()->with('error', 'Actividad seleccionada no válida.');
        }

        
        $now = Carbon::now(); 
        $investigacion = Investigaciones::findOrFail($request->idInvestigacion);

        $fechaMasAntigua = $investigacion->FechaAprobacion;
        
        if (is_null($fechaMasAntigua)) {
            $fechaMasAntigua = Carbon::parse($investigacion->created_at); // Aseguramos que sea un objeto Carbon
        } else {
            $fechaMasAntigua = Carbon::parse($fechaMasAntigua); // Aseguramos que sea un objeto Carbon
        }
        
        if (Carbon::parse($request->fecha)->lessThan($fechaMasAntigua)) {
            return redirect()->back()->with('error', 'La fecha de la actividad debe ser mayor a la fecha de asignación de la investigación (' . $fechaMasAntigua->format('Y-m-d') . ').');
        } elseif (Carbon::parse($request->fecha)->greaterThan($now)) {
            return redirect()->back()->with('error', '¡Alerta del futuro! 🚀 La fecha de la actividad no puede ser después de hoy (' . $now->format('Y-m-d') . '). ¡Viajar en el tiempo no está permitido... todavía!');
        }
        $request['idUsuario'] = Auth::user()->id;
        $request['actividad'] = $actividadSeleccionada;
        TrazabilidadActividadesRealizadas::create($request->all());
        return back()->with('info', 'Actividad registrada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TrazabilidadActividadesRealizadas $trazabilidadActividadesRealizadas)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TrazabilidadActividadesRealizadas $trazabilidadActividadesRealizadas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, TrazabilidadActividadesRealizadas $trazabilidadActividadesRealizadas)
    // {
    //     //
    // }
    public function update(Request $request, $id)
    {
        $actividadestipoinvestigacion = json_decode($request->input('actividadestipoinvestigacion'), true);

        $indice = $request->input('actividad');

        if (array_key_exists($indice, $actividadestipoinvestigacion)) {
            $actividadSeleccionada = $actividadestipoinvestigacion[$indice];
        } else {
            return redirect()->back()->with('error', 'Actividad seleccionada no válida.');
        }

        $actividad = TrazabilidadActividadesRealizadas::findOrFail($id);

        // $investigacion = Investigaciones::findOrFail($actividad->idInvestigacion);

        $now = Carbon::now()->startOfMinute(); // Remueve segundos y microsegundos
        // $investigacion = Investigaciones::findOrFail($request->idInvestigacion);

        $investigacion = Investigaciones::findOrFail($actividad->idInvestigacion);

        $fechaMasAntigua = $investigacion->FechaAprobacion;
        
        
        if (is_null($fechaMasAntigua)) {
            $fechaMasAntigua = Carbon::parse($investigacion->created_at)->startOfMinute();
        } else {
            $fechaMasAntigua = Carbon::parse($fechaMasAntigua)->startOfMinute();
        }

        $requestFecha = Carbon::parse($request->fecha)->startOfMinute(); // Normalizamos la fecha de la solicitud

        if ($requestFecha->lessThan($fechaMasAntigua)) {
            return redirect()->back()->with('error', 'La fecha de la actividad debe ser mayor a la fecha de asignación de la investigación (' . $fechaMasAntigua->format('Y-m-d H:i') . ').');
        } elseif ($requestFecha->greaterThan($now)) {
            return redirect()->back()->with('error', '¡Alerta del futuro! 🚀 La fecha de la actividad no puede ser después de hoy (' . $now->format('Y-m-d H:i') . '). ¡Viajar en el tiempo no está permitido... todavía!');
        }
        
        $request->validate([
            'actividad' => 'required|string|max:255',
            'observacion' => 'required|string|max:255',
            'fecha' => 'required|date',
        ]);

        $actividad->update([
            'actividad' => $actividadSeleccionada,
            'observacion' => $request->observacion,
            'fecha' => $request->fecha,
        ]);

        return redirect()->back()->with('info', 'Actividad actualizada exitosamente.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TrazabilidadActividadesRealizadas $trazabilidadActividadesRealizadas)
    {
        //
    }
}
