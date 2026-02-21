<?php

namespace App\Http\Middleware\Profilo;

use Closure;
use Illuminate\Http\Request;
use App\Services\Profilo\gestoreService;
use Symfony\Component\HttpFoundation\Response;

class IsAuthenticatedAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se l'utente è già loggato
        $gestoreservice = new GestoreService();
        if ($gestoreservice->getUtenteAutenticato())
            // Redirect alla route index o home
             return $next($request);

        // Altrimenti lascia proseguire la richiesta
        
        return redirect()->route('loginAdmin');
    }
}
