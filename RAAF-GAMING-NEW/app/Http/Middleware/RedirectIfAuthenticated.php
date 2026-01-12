<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Se l'utente è già loggato
        if ($request->session()->get('log', false) === true) {
            // Redirect alla route index o home
            return redirect()->route('home'); 
        }

        // Altrimenti lascia proseguire la richiesta
        return $next($request);
    }
}
