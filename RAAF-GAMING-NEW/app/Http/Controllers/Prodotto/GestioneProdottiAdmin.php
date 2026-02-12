<?php

namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use App\Services\Magazzino\PresenteInServices;
use App\Services\Prodotto\ProdottoService;
use Illuminate\Http\Request;

class GestioneProdottiAdmin extends Controller
{
    public function homeProdotto(Request $request)
    {
        $prodottoService = new ProdottoService();
        $presenteInService = new PresenteInServices();
        $prodotti = $prodottoService->allElements('codice_prodotto asc');
        foreach($prodotti as $prodotto)
            $prodotto->quantita_disponibile = $presenteInService->quantitaTotaleProdotto($prodotto->codice_prodotto);

        $data = [
            'prodotti' => $prodotti,
        ];

        return view('PresentazioneProdotto.paginaAmministratore',compact('data'));
    }
}
