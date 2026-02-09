<?php

namespace App\Http\Controllers;

use App\Models\Notificaciones;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class NotificacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
     
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    

    public function Listado()
    {

        $notificaciones = Notificaciones::where('idUsuario',Auth::user()->id)->orderBy('created_at','DESC')->get();
        $count = Notificaciones::where('idUsuario',Auth::user()->id)->where('leido',0)->count();
        return view('usuarios.notificaciones', compact('notificaciones','count'));
    }

    public function Leido(int $id)
    {

        $notificaciones = Notificaciones::find( $id);
        $notificaciones->leido =  1;
        $notificaciones->update(['leido' => 1]);
        return back()->with('info', 'Información actualizada correctamente.' );
                    
    }
    
    public function LeidoTodo()
    {
        
        $sql = ' update notificaciones set leido = 1 where idUsuario = ' .  Auth::user()->id  ;
                
        DB::update($sql);       

        
        return back()->with('info', 'Información actualizada correctamente.' );
                    
    }
    
    public function Cumplido(int $id)
    {

        $notificaciones = Notificaciones::find( $id);
        $notificaciones->pendiente =  0;
        $notificaciones->update(['pendiente' => 0]);
        return back()->with('info', 'Información actualizada correctamente.' );
                    
    }
    public function CumplidoTodo()
    {

        $sql = ' update notificaciones set pendiente = 0 where idUsuario = ' .  Auth::user()->id  ;
                
        DB::update($sql);       

        return back()->with('info', 'Información actualizada correctamente.' );
                    
    }



}
