<?php

namespace App\Http\Middleware\Prodotto;

use App\Services\Profilo\GestoreService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GestoreProdottiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Se l'utente è già loggato
        $gestoreservice = new GestoreService();
        $gestore = $gestoreservice->getUtenteAutenticato();
        if ($gestore->ruolo != 'ordine')
            // Lascia proseguire la richiesta
             return $next($request);

        // Redirect alla route index o home di ordine
        return redirect()->route('homeOrdine'); 
    }
}
