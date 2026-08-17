<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            // Si el estado NO es activo, o si la fecha de vencimiento ya pasó, redirigir al pago
            $isNotActive = $user->suscripcion_estado !== 'activo';
            $isExpired = $user->suscripcion_vencimiento && Carbon::parse($user->suscripcion_vencimiento)->isPast();

            if ($isNotActive || $isExpired) {
                return redirect()->route('suscripcion.pagar');
            }
        }

        return $next($request);
    }
}