<?php
namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Prodotto\ProdottoService;
use App\Services\Prodotto\RecensisceService;
use App\Services\Prodotto\CategoriaService;
use App\Services\Prodotto\ParteDiService;
use App\Services\Profilo\ClienteService;

class Prodotto extends Controller
{
    public function index(Request $request)
        {
            $prodottoService = new ProdottoService();
            $prodotti = $prodottoService->getTop6Home();

            return view ('PresentazioneProdotto.homepage',compact('prodotti'));
        }

    public function ricercaProdotto (Request $request)
        {
            $prodottoService = new ProdottoService();
            $stringa = $request->input('ricerca');
            $prodotti = $prodottoService->ricercaPerSottostringa($stringa);

            return view ('PresentazioneProdotto.paginaRicerca',compact('prodotti'));
        }

    public function show(Request $request)
        {
            $codice = $request->input('codice');
            $prodottoService = new ProdottoService();
            $prodotto = $prodottoService->ricercaPerChiave($codice);

            return view ("PresentazioneProdotto.paginaGioco",compact('prodotto'));
        }

    public function aggiungiRecensione(Request $request)
    {
        $clienteService = new ClienteService();
        $recensioneService = new RecensisceService();

        
        $clienteLoggato = $clienteService->getUtenteAutenticato();

        $successo = $recensioneService->pubblicaRecensione(
            $clienteLoggato->email,      
            $request->input('prodotto'),
            $request->input('voto'),
            $request->input('commento')
        );

        if ($successo) {
            return redirect()->back()->with('success', 'Recensione pubblicata!');
        }

        return redirect()->back()->with('error', 'Hai già recensito questo prodotto.');
    }

    public function getImmagine($codice)
    {
        $prodottoService = new ProdottoService();
        $prodotto = $prodottoService->ricercaPerChiave($codice);
        
        if (!$prodotto || !$prodotto->copertina) {
            abort(404, 'Immagine non trovata');
        }
        
        return response($prodotto->copertina)
            ->header('Content-Type', 'image/jpeg');
    }

    public function ricercaPerCategoria(Request $request)
    {
        $categoria = $request->categoria;

        $categoriaService = new CategoriaService();
        $prodottoService = new ProdottoService();
        if($categoria=='catalogo')
        {
           $prodotti =  $prodottoService->allElements('codice_prodotto asc');
           return view('PresentazioneProdotto.paginaRicerca', compact('prodotti'));
        }
        else if($categoria=='abbonamento')
        {
            $prodotti =  $prodottoService->allElements('codice_prodotto asc');
            foreach($prodotti as $prodotto)
            {
                if(!$prodotto->abbonamento)
                {
                    $prodotti = $prodotti->reject(function($pd) use ($prodotto)
                    {
                        return $pd->codice_prodotto === $prodotto->codice_prodotto;
                    }); 
                }           
            }
           return view('PresentazioneProdotto.paginaRicerca', compact('prodotti'));
        }
        else if($categoria=='console')
        {
            $prodotti =  $prodottoService->allElements('codice_prodotto asc');
            foreach($prodotti as $prodotto)
            {
                if(!$prodotto->console)
                {
                    $prodotti = $prodotti->reject(function($pd) use ($prodotto)
                    {
                        return $pd->codice_prodotto === $prodotto->codice_prodotto;
                    }); 
                }           
            }
           return view('PresentazioneProdotto.paginaRicerca', compact('prodotti'));
        }
        else if($categoria=='dlc')
        {
            $prodotti =  $prodottoService->allElements('codice_prodotto asc');
            foreach($prodotti as $prodotto)
            {
                if(!$prodotto->dlc)
                {
                    $prodotti = $prodotti->reject(function($pd) use ($prodotto)
                    {
                        return $pd->codice_prodotto === $prodotto->codice_prodotto;
                    }); 
                }           
            }
           return view('PresentazioneProdotto.paginaRicerca', compact('prodotti'));
        }
        else if($categoria=='videogioco')
        {
            $prodotti =  $prodottoService->allElements('codice_prodotto asc');
            foreach($prodotti as $prodotto)
            {
                if(!$prodotto->videogioco)
                {
                    $prodotti = $prodotti->reject(function($pd) use ($prodotto)
                    {
                        return $pd->codice_prodotto === $prodotto->codice_prodotto;
                    }); 
                }           
            }
           return view('PresentazioneProdotto.paginaRicerca', compact('prodotti'));
        } 
        else
        {
            $parteDi = $categoriaService->allElements('nome asc');
            $tipoGioco = $parteDi->firstWhere('nome', $categoria);
            if(!isset($tipoGioco))
                return redirect()->route('home');

            $prodotti = collect();
            foreach($tipoGioco->parteDi as $categoriaScelta)
                $prodotti->push($categoriaScelta?->getVideogioco?->getProdotto);
            return view('PresentazioneProdotto.paginaRicerca', compact('prodotti'));
        }
    }  
}
