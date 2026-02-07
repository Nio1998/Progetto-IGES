<?php

namespace App\Services\Magazzino;

use App\Models\Magazzino\Magazzino;
use Ramsey\Collection\Collection;
use Illuminate\Support\Facades\Cache;

class MagazzinoService
{

    private const CACHE_TTL = 60;
    private const CACHE_KEY = 'Magazzini';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Carica tutti i magazzini nella cache se non sono già presenti o se la cache è scaduta.
     *
     * @return Collection<Magazzino>
     */
    private function loadMagazzini(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return Magazzino::get()->keyBy('indirizzo');
        });
    }

    /**
     * Cerca un magazzino per chiave (indirizzo).
     * Prima controlla la cache, se scaduta si ricarica tutto dal DB.
     *
     * @param string|null $id L'indirizzo del magazzino da cercare.
     * @return Magazzino|null Il modello Magazzino trovato o null.
     * @throws \InvalidArgumentException Se l'id fornito è vuoto o null.
     */
    public function ricercaPerChiave(?string $id): ?Magazzino
    {
        if ($id === null || $id === '')
            throw new \InvalidArgumentException("Inserito un id null o vuoto");

        $magazzini = $this->loadMagazzini();

        return $magazzini->get($id) ?? null;
    }

    /**
     * Ritorna una Collection di Magazzini ordinati.
     * Preleva dalla cache e applica l'ordinamento.
     *
     * @param string|null $ordinamento La colonna su cui applicare l'ordinamento (es. 'indirizzo asc').
     * @return Collection<Magazzino> Una collezione di oggetti Magazzino ordinati.
     * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
     */
    public function allElements(?string $ordinamento): Collection
    {
        if ($ordinamento === null || $ordinamento === '')
            throw new \InvalidArgumentException("Inserito un ordinamento null o vuoto");

        $magazzini = $this->loadMagazzini();

        switch ($ordinamento)
        {
            case 'indirizzo asc':
                $magazzini = $magazzini->sortBy('indirizzo');
                break;
            case 'indirizzo desc':
                $magazzini = $magazzini->sortByDesc('indirizzo');
                break;
            case 'capienza asc':
                $magazzini = $magazzini->sortBy('capienza');
                break;
            case 'capienza desc':
                $magazzini = $magazzini->sortByDesc('capienza');
                break;
            default:
                throw new \InvalidArgumentException("Ordinamento scritto in modo errato");
        }

        return $magazzini;
    }
}
