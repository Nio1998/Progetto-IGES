<?php

namespace App\Http\Middleware\Profilo;

use Closure;
use Illuminate\Http\Request;
use App\Services\Profilo\gestoreService;
use Symfony\Component\HttpFoundation\Response;

class RedirectAdminIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Se l'utente è già loggato
        $gestoreservice = new GestoreService();
        if ($gestoreservice->getUtenteAutenticato()){
            $ruolo = $gestoreservice->getUtenteAutenticato()->ruolo;
            if($ruolo == 'ordine'){
                return redirect()->route('homeOrdine'); 
            }
            else
                return redirect()->route('homeProdotto'); 
        }
        // Altrimenti lascia proseguire la richiesta
        return $next($request);
    }
}
