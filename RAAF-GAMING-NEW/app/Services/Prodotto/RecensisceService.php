<?php

namespace App\Services\Prodotto;

use App\Models\Prodotto\Recensisce;
use Illuminate\Support\Collection;

class RecensisceService
{
    public function __construct()
    {
        //
    }

    /**
     * Unisce la logica di 'ricercaPerChiave' e 'newInsert'.
     *
     * @param string $cliente   L'email o username del cliente
     * @param int $prodottoId   L'ID del prodotto
     * @param int $voto         Il voto (es. 1-5)
     * @param string $commento  Il testo della recensione
     * * @return bool           restituisce true se inserisce la nuova recensione
     * @throws InvalidArgumentException Se la recensione esiste già
     */

    public function pubblicaRecensione(string $cliente, int $prodottoId, int $voto, string $commento): bool
    {
        // 1. Logica di Controllo (ex ricercaPerChiave)
        // Verifichiamo se esiste già una recensione per questa coppia cliente/prodotto
        $esiste = Recensisce::where('cliente', $cliente)
                            ->where('prodotto', $prodottoId)
                            ->exists(); // Restituisce true o false

        if ($esiste) {
            // Questo corrisponde al tuo blocco 'else' che restituiva il JSON di errore
            return false;
        }

        // 2. Logica di Inserimento (ex newInsert)
        // Se siamo arrivati qui, la recensione non esiste, quindi la creiamo
        Recensisce::create([
            'cliente'  => $cliente,
            'prodotto' => $prodottoId,
            'voto'     => $voto,
            'commento' => $commento
        ]);


        return true;
    }

    /**
     * Ritorna una Collection di Recensisce ordinati.
     *
     * @param string|null $ordinamento La colonna su cui applicare l'ordinamento (es. 'cliente asc').
     * @return Collection<Recensisce> Una collezione di oggetti Recensisce ordinati.
     * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
     */
    public function allElements(?string $ordinamento): Collection
    {
        if ($ordinamento == null || $ordinamento == '')
            throw new \InvalidArgumentException("ordinamento vuoto o null");

        switch ($ordinamento)
        {
            case 'cliente asc':
                $recensisce = Recensisce::orderBy('cliente', 'asc')->get();
                break;
            case 'cliente desc':
                $recensisce = Recensisce::orderBy('cliente', 'desc')->get();
                break;
            case 'prodotto asc':
                $recensisce = Recensisce::orderBy('prodotto', 'asc')->get();
                break;
            case 'prodotto desc':
                $recensisce = Recensisce::orderBy('prodotto', 'desc')->get();
                break;
            case 'voto asc':
                $recensisce = Recensisce::orderBy('voto', 'asc')->get();
                break;
            case 'voto desc':
                $recensisce = Recensisce::orderBy('voto', 'desc')->get();
                break;
            case 'commento asc':
                $recensisce = Recensisce::orderBy('commento', 'asc')->get();
                break;
            case 'commento desc':
                $recensisce = Recensisce::orderBy('commento', 'desc')->get();
                break;
            default:
                throw new \InvalidArgumentException("ordinamento non valido");
        }

        return $recensisce;
    }

}