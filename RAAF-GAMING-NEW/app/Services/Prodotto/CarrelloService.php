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
     * Recupera il carrello dalla sessione (se non esiste crea una collection vuota).
     * Verifica se il prodotto è già presente nel carrello:
     * - Se NON è presente, lo aggiunge alla collection e aggiorna la sessione.
     * - Se è già presente, non lo aggiunge nuovamente.
     *
     * @param Prodotto $prodotto Il prodotto da aggiungere al carrello
     * @return bool Restituisce true se il prodotto era già presente, false se è stato aggiunto
     */
    public function aggiungiAlCarrello(Prodotto $prodotto): bool
    {
        $carrello = session()->get('Carrello', collect());

        $esiste = $carrello->contains(function ($item) use ($prodotto) {
            return $item->id === $prodotto->id;
        });

        if (!$esiste) {
            $carrello->push($prodotto);
            session()->put('Carrello', $carrello);
        }

        return $esiste;
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
