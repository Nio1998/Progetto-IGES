<?php

namespace App\Services\Prodotto;

use App\Models\Prodotto\Prodotto;
use Illuminate\Support\Collection;

class CarrelloService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Aggiunge un prodotto al carrello nella sessione.
     * 
     * Recupera il carrello dalla sessione (se non esiste crea una collection vuota),
     * aggiunge il prodotto alla collection e salva nella sessione.
     * 
     * @param Prodotto $prodotto Il prodotto da aggiungere al carrello
     * @return void
     */
    public function aggiungiAlCarrello(Prodotto $prodotto): void
    {
        // Recupera il carrello dalla sessione o crea una collection vuota
        $carrello = session()->get('Carrello', collect());

        // Aggiunge il prodotto al carrello
        $carrello->push($prodotto);

        // Salva il carrello aggiornato nella sessione
        session()->put('Carrello', $carrello);
    }

    /**
     * Svuota completamente il carrello rimuovendolo dalla sessione.
     * 
     * @return void
     */
    public function svuotaCarrello(): void
    {
        // Rimuove completamente il carrello dalla sessione
        session()->forget('Carrello');
    }

    /**
     * Restituisce tutti i prodotti presenti nel carrello.
     * 
     * Se il carrello non esiste nella sessione, restituisce una collection vuota.
     * 
     * @return \Illuminate\Support\Collection Collection di prodotti nel carrello
     */
    public function getProdottiCarrello(): Collection
    {
        // Recupera il carrello dalla sessione o restituisce una collection vuota
        return session()->get('Carrello', collect());
    }
}
