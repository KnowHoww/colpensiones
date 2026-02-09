<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class CheckUserPasswordChanges
{
    public function handle($request, Closure $next)
    {
        // Verificar si el usuario está autenticado
        if (Auth::check()) {
            $user = Auth::user();
            $usuario = User::find(Auth::user()->id);
            // Verificar si se requiere actualizar la contraseña
            if ($user->ActualizarPassword == 0 || Carbon::parse($user->fechaActualizarPassword)->addDays(30)->isPast()) {
                return redirect()->route('edituser');
            }
    
            // Verificar si la contraseña ha cambiado desde la última solicitud
            if ($user->password_changed_at !== null && $user->password_changed_at > $request->session()->get('last_password_change_time')) {
                // La contraseña ha cambiado, cerrar sesión y redirigir al usuario al inicio de sesión
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tu contraseña ha sido cambiada. Por favor inicia sesión nuevamente.');
            }
        }
    
        return $next($request);
    }
}
