<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cookie;

class CheckPersistentCookies
{
    public function handle($request, Closure $next)
    {
        // Verificar si existe una cookie persistente
        if ($request->cookie('persistent_cookie')) {
            // Verificar la expiración de la cookie
            $cookieExpiration = Cookie::get('persistent_cookie');
            if (strtotime($cookieExpiration) < time()) {
                // La cookie ha expirado, eliminarla
                Cookie::queue(Cookie::forget('persistent_cookie'));
            }
        }

        return $next($request);
    }
}
