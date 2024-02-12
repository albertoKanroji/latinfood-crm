<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyWebhookSecret
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        // Obtén el secreto del webhook desde la configuración de tu aplicación
        $webhookSecret = config('app.webhook_secret');

        // Verifica si el secreto del webhook coincide con el valor esperado
        if ($request->header('X-Webhook-Secret') !== $webhookSecret) {
            // Si el secreto no coincide, puedes lanzar una excepción o responder con un error
            return response()->json(['message' => 'Webhook secret mismatch'], 401);
        }

        return $next($request);
    }
}
