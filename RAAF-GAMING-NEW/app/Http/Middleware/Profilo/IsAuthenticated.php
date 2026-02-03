<?php

namespace App\Http\Middleware\Profilo;

use Closure;
use Illuminate\Http\Request;
use App\Services\Profilo\ClienteService;
use Symfony\Component\HttpFoundation\Response;

class IsAuthenticated
{

    public function handle(Request $request, Closure $next): Response
    {
        // Se l'utente è già loggato
        $clienteService = new ClienteService();
        if ($clienteService->getUtenteAutenticato())
           return $next($request);

        // Redirect alla route index o home
        return redirect()->route('home'); 
    }
}
