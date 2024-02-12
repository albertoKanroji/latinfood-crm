<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
         foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            // Verificar si el usuario tiene el rol de empleado
            if (Auth::guard($guard)->user()->hasRole('Employee')) {
                // Redirigir al empleado a una página específica
                return redirect()->route('/products');
            }

            // Redirigir a la página de inicio si no es un empleado
            return redirect(RouteServiceProvider::HOME);
        }
    }

        return $next($request);
    }
}
