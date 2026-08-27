<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si hay un usuario logueado, lo dejamos pasar libremente al dashboard
        if ($user) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}