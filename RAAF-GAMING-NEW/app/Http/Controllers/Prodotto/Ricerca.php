<?php

namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use App\Services\Prodotto\ProdottoService;
use Illuminate\Http\Request;

class Ricerca extends Controller
{
    public function ricerca (Request $request)
    {
        $prodottoService = new ProdottoService();
        $stringa = $request->input('ricerca');
        $prodotti = $prodottoService->ricercaPerSottostringa($stringa);

        return view ('prodotto.paginaRicerca',compact('prodotti'));
    }
}
