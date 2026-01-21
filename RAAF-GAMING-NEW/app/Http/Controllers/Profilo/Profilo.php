<?php

namespace App\Http\Controllers\Profilo;

use App\Http\Controllers\Controller;
use App\Services\Profilo\CartaFedeltaService;
use App\Services\Profilo\ClienteService;
use Illuminate\Http\Request;

class Profilo extends Controller
{
    public function mostraProfilo()
    {
        $clienteService = new ClienteService();
        $cliente = $clienteService->getUtenteAutenticato();

        $data = [
            'cliente' => $cliente,
        ];

        return view('PresentazioneProfilo.profilo', compact('data'));
    }
}
