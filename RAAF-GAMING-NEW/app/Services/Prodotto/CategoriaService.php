<?php

namespace App\Services\Prodotto;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Models\Prodotto\Categoria;

/**
 * Servizio per la gestione dei Categorie.
 *
 * Fornisce metodi per recuperare tutti i Categorie con caching integrato.
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
     * Chiave con cui i dati dei Categorie vengono salvati in cache.
     */
    private const CACHE_KEY = 'Categorie';

    /**
     * Crea una nuova istanza del servizio.
     */
    public function __construct()
    {
        //
    }

    /**
     * Carica tutti i Categorie dalla cache, o dal database se non presenti in cache.
     *
     * @return Collection<int, Fornitore> Collection indicizzata automaticamente.
     */
    private function loadCategorie(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Categoria::all()->keyBy('nome'); // o altro campo se vuoi indicizzare per nome
        });
    }

    /**
     * Restituisce tutti i Categorie ordinati secondo il parametro passato.
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

        $categorie = $this->loadCategorie();

        switch ($ordinamento) {
            case 'nome asc':
                $categorie = $categorie->sortBy('nome');
                break;
            case 'nome desc':
                $categorie = $categorie->sortByDesc('nome');
                break;
            default:
                throw new \InvalidArgumentException("Ordinamento scritto in modo errato");
        }

        return $categorie;
    }
}
