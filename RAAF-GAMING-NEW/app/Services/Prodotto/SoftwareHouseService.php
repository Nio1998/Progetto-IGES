<?php

namespace App\Services\Prodotto;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use App\Models\Prodotto\SoftwareHouse;

/**
 * Servizio per la gestione delle SoftwareHouse.
 *
 * Fornisce metodi per recuperare SoftwareHouse singole o tutte, con caching integrato.
 */
class SoftwareHouseService
{
    /**
     * Tempo di vita della cache in minuti.
     *
     * I dati salvati in cache saranno considerati validi per questo intervallo di tempo.
     */
    private const CACHE_TTL = 60;

    /**
     * Chiave con cui i dati delle SoftwareHouse vengono salvati in cache.
     */
    private const CACHE_KEY = 'SoftwareHouse';

    /**
     * Crea una nuova istanza del servizio.
     */
    public function __construct()
    {
        //
    }

    /**
     * Carica tutte le SoftwareHouse dalla cache, o dal database se non presenti in cache.
     *
     * @return Collection<string, SoftwareHouse> Collection indicizzata per 'nomesfh'.
     */
    private function loadSoftwareHouse(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return SoftwareHouse::get()->keyBy('nomesfh');
        });
    }

    /**
     * Recupera una SoftwareHouse per chiave primaria.
     *
     * Metodo migrato uno-a-uno dal DAO Java.
     * Attualmente non implementato e restituisce sempre null.
     *
     * @param string $code Chiave primaria della SoftwareHouse
     * @return SoftwareHouse|null
     */
    public function doRetriveByKey(string $code)
    {
        return null;
    }

    /**
     * Restituisce tutte le SoftwareHouse ordinate secondo il parametro passato.
     *
     * @param string|null $ordinamento Ordinamento richiesto, ad esempio 'nomesfh asc' o 'nomesfh desc'.
     * @return Collection<string, SoftwareHouse> Collection ordinata indicizzata per 'nomesfh'.
     *
     * @throws \InvalidArgumentException Se l'ordinamento è null, vuoto o non riconosciuto.
     */
    public function allElements(?string $ordinamento): Collection
    {
        if ($ordinamento === null || $ordinamento === '') {
            throw new \InvalidArgumentException("Inserito un ordinamento null o vuoto");
        }

        $softwarehouses = $this->loadSoftwareHouse();

        switch ($ordinamento) {
            case 'nomesfh asc':
                $softwarehouses = $softwarehouses->sortBy('nomesfh');
                break;
            case 'nomesfh desc':
                $softwarehouses = $softwarehouses->sortByDesc('nomesfh');
                break;
            default:
                throw new \InvalidArgumentException("Ordinamento scritto in modo errato");
        }

        return $softwarehouses;
    }
}
