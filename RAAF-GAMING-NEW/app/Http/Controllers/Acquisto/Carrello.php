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

    public function eliminaCarrello(Request $request)
    {
        $carrelloService = new CarrelloService();
        $id = $request->input('id');

        if (empty($id))
            return redirect()->route('carrello.show');

        $carrello = $carrelloService->getProdottiCarrello();

        if ($carrello->isEmpty())
            return redirect()->route('carrello.show');

        $carrelloService->rimuoviDalCarrello($id);

        return redirect()->route('carrello.show');
    }
}
