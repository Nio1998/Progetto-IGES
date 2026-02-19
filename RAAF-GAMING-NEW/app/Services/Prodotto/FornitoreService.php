<?php

namespace App\Services\Prodotto;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Models\Prodotto\Fornitore;

/**
 * Servizio per la gestione dei Fornitori.
 *
 * Fornisce metodi per recuperare tutti i Fornitori con caching integrato.
 */
class FornitoreService
{
    /**
     * Tempo di vita della cache in minuti.
     *
     * I dati salvati in cache saranno considerati validi per questo intervallo di tempo.
     */
    private const CACHE_TTL = 60;

    /**
     * Chiave con cui i dati dei Fornitori vengono salvati in cache.
     */
    private const CACHE_KEY = 'Fornitori';

    /**
     * Crea una nuova istanza del servizio.
     */
    public function __construct()
    {
        //
    }

    /**
     * Carica tutti i Fornitori dalla cache, o dal database se non presenti in cache.
     *
     * @return Collection<int, Fornitore> Collection indicizzata automaticamente.
     */
    private function loadFornitori(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Fornitore::all()->keyBy('nome'); // o altro campo se vuoi indicizzare per nome
        });
    }

    /**
     * Restituisce tutti i Fornitori ordinati secondo il parametro passato.
     *
     * @param string|null $ordinamento Ordinamento richiesto, ad esempio 'nome asc' o 'nome desc'.
     * @return Collection<int, Fornitore> Collection ordinata.
     *
     * @throws \InvalidArgumentException Se l'ordinamento è null, vuoto o non riconosciuto.
     */
    public function allElements(?string $ordinamento): Collection
    {
        if ($ordinamento === null || $ordinamento === '') {
            throw new \InvalidArgumentException("Inserito un ordinamento null o vuoto");
        }

        $fornitori = $this->loadFornitori();

        switch ($ordinamento) {
            case 'nome asc':
                $fornitori = $fornitori->sortBy('nome');
                break;
            case 'nome desc':
                $fornitori = $fornitori->sortByDesc('nome');
                break;
            case 'indirizzo asc':
                $fornitori = $fornitori->sortBy('indirizzo');
                break;
            case 'indirizzo desc':
                $fornitori = $fornitori->sortByDesc('indirizzo');
                break;
            case 'telefono asc':
                $fornitori = $fornitori->sortBy('telefono');
                break;
            case 'telefono desc':
                $fornitori = $fornitori->sortByDesc('telefono');
                break;
            default:
                throw new \InvalidArgumentException("Ordinamento scritto in modo errato");
        }

        return $fornitori;
    }
}
