<?php

namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Prodotto\ProdottoService;
use App\Services\Prodotto\RecensisceService;

class Prodotto extends Controller
{
    public function index(Request $request)
        {
            $prodottoService = new ProdottoService();
            $prodotti = $prodottoService->getTop6Home();

            return view ('prodotto.homepage',compact('prodotti'));
        }

    public function ricercaProdotto (Request $request)
        {
            $prodottoService = new ProdottoService();
            $stringa = $request->input('ricerca');
            $prodotti = $prodottoService->ricercaPerSottostringa($stringa);

            return view ('prodotto.paginaRicerca',compact('prodotti'));
        }

    public function show(Request $request)
        {
            $codice = $request->input('codice');
            $prodottoService = new ProdottoService();
            $prodotto = $prodottoService->ricercaPerChiave($codice);

            return view ("prodotto.paginaGioco",compact('prodotto'));
        }

    public function aggiungiRecensione(Request $request)
        {
            $recensioneService = new RecensisceService();
            //$recensione = $recensioneService->pubblicaRecensione()
        
        }
}
