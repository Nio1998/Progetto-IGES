<?php

namespace App\Http\Middleware\Profilo;

use App\Services\Profilo\ClienteService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // Se l'utente è già loggato
        $clienteService = new ClienteService();
        if ($clienteService->getUtenteAutenticato())
            // Redirect alla route index o home
            return redirect()->route('home'); 

        // Altrimenti lascia proseguire la richiesta
        return $next($request);
    }
}
