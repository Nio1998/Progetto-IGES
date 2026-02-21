<?php

namespace App\Http\Controllers\Acquisto;

use App\Http\Controllers\Controller;
use App\Services\Acquisto\CorriereEspressoService;
use App\Services\Acquisto\OrdineService;
use Illuminate\Http\Request;

class GestioneOrdiniAdmin extends Controller
{
    public function homeOrdine(Request $request)
    {
        $ordineService = new OrdineService();
        $corriereService = new CorriereEspressoService();

        $ordiniNonConsegnati = $ordineService->getOrdiniNonConsegnati();
        $corrieri = $corriereService->allElements('nome asc');

        $data = [
            'ordiniNonConsegnati' => $ordiniNonConsegnati,
            'corrieri'            => $corrieri,
        ];

        return view('PresentazioneAcquisto.paginaGestioneOrdini', compact('data'));
    }
}
