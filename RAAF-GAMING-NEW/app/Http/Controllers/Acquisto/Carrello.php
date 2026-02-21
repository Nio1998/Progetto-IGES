<?php

namespace App\Http\Controllers\Acquisto;

use App\Http\Controllers\Controller;
use App\Models\Acquisto\Ordine;
use App\Models\Acquisto\Riguarda;
use App\Services\Acquisto\OrdineService;
use App\Services\Magazzino\PresenteInServices;
use App\Services\Prodotto\CarrelloService;
use App\Services\Profilo\CartaFedeltaService;
use App\Services\Profilo\ClienteService;
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

    public function confermaAcquisto(Request $request)
    {
        $carrelloService     = new CarrelloService();
        $ordineService       = new OrdineService();
        $presenteInService   = new PresenteInServices();
        $cartaFedeltaService = new CartaFedeltaService();
        $clienteService      = new ClienteService();

        $cliente = $clienteService->getUtenteAutenticato();

        // Se carrello vuoto rimanda al carrello
        $prodotti = $carrelloService->getProdottiCarrello();
        if ($prodotti->isEmpty())
            return redirect()->route('carrello.show');

        $indirizzoConsegna = $request->input('indirizzodiconsegna');

        // Controllo disponibilità prodotti e calcolo magazzini
        $magazziniDisponibili = collect();
        $nonDisponibili = false;

        foreach ($prodotti as $prodotto) {
            $disponibilita = $presenteInService->getDisponibilita($prodotto, 1);

            if ($disponibilita->isEmpty()) {
                $carrelloService->rimuoviDalCarrello($prodotto->codice_prodotto);
                $nonDisponibili = true;
            } else {
                $magazziniDisponibili->push($disponibilita->first());
            }
        }

        if ($nonDisponibili)
            return redirect()->route('carrello.show')->with('error', 'Qualche o tutti i prodotti nel carrello non sono piu\' disponibili');

        // Creo il nuovo ordine
        $ordine = new Ordine();
        $ordine->codice = $ordineService->generaCodiceOrdine();
        $ordine->cliente = $cliente->email;
        $ordine->data_acquisto = now()->toDateString();
        $ordine->metodo_di_pagamento = $cliente->cartacredito?->codicecarta ?? null;
        $ordine->indirizzo_di_consegna = $indirizzoConsegna;
        $ordine->stato = 'elaborazione';
        $ordine->gestore = null;
        $ordine->prezzo_totale = $prodotti->sum(fn($p) => $p->prezzo_effettivo);

        // Costruisco la lista di riguarda
        $riguardaList = $prodotti->map(function ($prodotto) {
            $riguarda = new Riguarda();
            $riguarda->prodotto = $prodotto->codice_prodotto;
            $riguarda->quantita_acquistata = 1;
            return $riguarda;
        });

        try {
            $ordineService->newInsert($ordine, $riguardaList);

            foreach ($magazziniDisponibili as $info)
                $presenteInService->doUpdate($info['presente_in'], $info['quantita']);

            $cartaFedeltaService->doUpdate($cliente->cartafedelta);
            $carrelloService->svuotaCarrello();

            return redirect()->route('carrello.show')->with('success', 'Acquisto Confermato');

        } catch (\Exception $e) {
            return redirect()->route('carrello.show')->with('error', 'Errore durante l\'acquisto, riprova.');
        }
    }
}
