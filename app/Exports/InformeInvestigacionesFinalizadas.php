<?php

namespace App\Exports;

use App\Models\InvestigacionesReportes;
use App\Models\InvestigacionesBeneficiarios;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class InformeInvestigacionesFinalizadas implements FromView
{
    protected $id;

    public function __construct($id)
    {
        return $this->id = $id;
    }

    public function view(): View
    {
        $data = InvestigacionesReportes::select(
            'investigador.name as name_investigador',
            'investigador.lastname as lastname_investigador',
            'coordinador.name as name_coordinador',
            'coordinador.lastname as lastname_coordinador',
            'analista.name as name_analista',
            'analista.lastname as lastname_analista',
            'investigaciones.*',
        )
            ->leftJoin('investigacion_asignacion', 'investigacion_asignacion.idInvestigacion', '=', 'investigaciones.id')
            ->leftJoin('users as coordinador', 'coordinador.id', '=', 'investigacion_asignacion.CoordinadorRegional')
            ->leftJoin('users as investigador', 'investigador.id', '=', 'investigacion_asignacion.Investigador')
            ->leftJoin('users as analista', 'analista.id', '=', 'investigacion_asignacion.Analista')
            ->where('investigaciones.estado', $this->id)
            ->get();

        return view('exports.InformeInvestigacionesEstado', ['data' => $data]);
    }
}
