<?php

namespace App\Http\Controllers\Acquisto;

use App\Http\Controllers\Controller;
use App\Services\Profilo\ClienteService;

class Ordini extends Controller
{
    public function __construct(protected ClienteService $clienteService) {}

    public function index()
    {
        $cliente = $this->clienteService->getUtenteAutenticato();

        $ordini = $cliente->effettua()->with('getSpedito')->get();

        return view('PresentazioneAcquisto.ordini', compact('ordini'));
    }
}