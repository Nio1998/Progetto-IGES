<?php
namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use App\Services\Prodotto\CarrelloService;
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
            $clienteService = new ClienteService();
            $cliente = $clienteService->getUtenteAutenticato();
            $prodotto = $prodottoService->ricercaPerChiave($codice);

            return view ("PresentazioneProdotto.paginaGioco",compact('prodotto','cliente'));
        }

    public function aggiungiRecensione(Request $request)
    {
        try {
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
                return response()->json([
                    'success' => true,
                    'message' => 'Recensione pubblicata!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Hai già recensito questo prodotto.'
            ], 422);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getImmagine(Request $request)
    {
        $prodottoService = new ProdottoService();
        $codice = $request->codice;
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

    public function aggiungiCarrello(Request $request)
    {
        $carrelloService = new CarrelloService();
        $prodottoService = new ProdottoService();

        $codiceProdotto = (int) $request->input('id');

        // Verifica che il prodotto esista
        $prodotto = $prodottoService->ricercaPerChiave($codiceProdotto);

        if ($prodotto == null) {
            return response()->json([
                'message' => 'Prodotto non trovato'
            ], 404);
        }

        // Prova ad aggiungere al carrello
        $aggiunto = $carrelloService->aggiungiAlCarrello($prodotto);

        if (!$aggiunto) {
            return response()->json([
                'message' => 'Aggiunta nel carrello fatta con successo!'
            ]);
        } else {
            return response()->json([
                'message' => 'Hai gia questo prodotto nell carrello'
            ]);
        }
    }
}
