<?php

namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use App\Services\Magazzino\PresenteInServices;
use App\Services\Prodotto\CategoriaService;
use App\Services\Prodotto\FornitoreService;
use App\Services\Prodotto\ProdottoService;
use App\Services\Prodotto\SoftwareHouseService;
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

        $fornitoreService = new FornitoreService();
        $fornitori = $fornitoreService->allElements("nome asc");

        $categoriaService = new CategoriaService();
        $categorie = $categoriaService->allElements("nome asc");

        $sfhService = new SoftwareHouseService();
        $softwarehouses = $sfhService->allElements("nomesfh asc");

        $data = [
            'prodotti'       => $prodotti,
            'fornitori'      => $fornitori,
            'categorie'      => $categorie,
            'softwarehouses' => $softwarehouses,
        ];

        return view('PresentazioneProdotto.paginaAmministratore',compact('data'));
    }
}
