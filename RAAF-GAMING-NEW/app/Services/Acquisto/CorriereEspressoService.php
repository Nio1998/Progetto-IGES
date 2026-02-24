<?php

namespace App\Services\Acquisto;

use App\Models\Acquisto\CorriereEspresso;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CorriereEspressoService
{
    private const CACHE_TTL = 60;
    private const CACHE_KEY = 'Corrieri';

    public function __construct()
    {
        //
    }

    /**
     * Carica tutti i corrieri nella cache se non sono già presenti o se la cache è scaduta.
     *
     * @return Collection<CorriereEspresso>
     */
    private function loadCorrieri(): Collection
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return CorriereEspresso::get()->keyBy('nome');
        });
    }

    /**
     * Ritorna una Collection di CorriereEspresso ordinati.
     *
     * @param string|null $ordinamento La colonna su cui applicare l'ordinamento (es. 'nome asc').
     * @return Collection<CorriereEspresso> Una collezione di oggetti CorriereEspresso ordinati.
     * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
     */
    public function allElements(?string $ordinamento): Collection
    {
        if ($ordinamento === null || $ordinamento === '')
            throw new \InvalidArgumentException("Inserito un ordinamento null o vuoto");

        $corrieri = $this->loadCorrieri();

        switch ($ordinamento)
        {
            case 'nome asc':
                $corrieri = $corrieri->sortBy('nome');
                break;
            case 'nome desc':
                $corrieri = $corrieri->sortByDesc('nome');
                break;
            case 'sito asc':
                $corrieri = $corrieri->sortBy('sito');
                break;
            case 'sito desc':
                $corrieri = $corrieri->sortByDesc('sito');
                break;
            default:
                throw new \InvalidArgumentException("Ordinamento scritto in modo errato");
        }

        return $corrieri;
    }
}