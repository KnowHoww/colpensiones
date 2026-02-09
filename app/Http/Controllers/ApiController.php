<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function cargaMunicipios($id)
    {
        $municipios = Municipio::where('departamento_id', $id)->get();
        return response()->json($municipios);
    }
}
