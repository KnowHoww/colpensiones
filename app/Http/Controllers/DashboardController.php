<?php

namespace App\Http\Controllers;

use App\Models\CentroCostos;
use App\Models\InvestigacionAsignacion;
use App\Models\Servicios;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $servicios = Servicios::all();
        $centroCosto = CentroCostos::where('id','!=',1)->get();
        return view('home',compact('servicios','centroCosto'));
    }
}
