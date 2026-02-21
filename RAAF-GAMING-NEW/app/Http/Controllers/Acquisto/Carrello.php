<?php

namespace App\Http\Controllers\Acquisto;

use App\Http\Controllers\Controller;
use App\Services\Prodotto\CarrelloService;
use Illuminate\Http\Request;

class Carrello extends Controller
{
    public function carrello(Request $request)
    {
        $carrelloService = new CarrelloService();
        $prodotti = $carrelloService->getProdottiCarrello();

        $data = [
            'prodotti' => $prodotti->isEmpty() ? null : $prodotti,
        ];

        return view('PresentazioneAcquisto.paginaCarrello', compact('data'));
    }
}
