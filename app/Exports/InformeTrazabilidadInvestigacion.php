<?php

namespace App\Exports;

use App\Models\TrazabilidadActividadesRealizadas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class InformeTrazabilidadInvestigacion implements FromView
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        $data = TrazabilidadActividadesRealizadas::select(
            'investigacion_trazabilidad_actividades_realizadas.*',
            'roles.name as rol_usuario'
        )
        ->join('users', 'investigacion_trazabilidad_actividades_realizadas.idUsuario', '=', 'users.id')
        ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
        ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
        ->where('investigacion_trazabilidad_actividades_realizadas.idInvestigacion', $this->id )
        ->get();
            
        return view('exports.InformeTrazabilidadInvestigacion', ['data' => $data]);
    }
}
