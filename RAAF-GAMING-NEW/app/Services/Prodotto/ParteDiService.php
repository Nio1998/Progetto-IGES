<?php

namespace App\Services\Prodotto;

use App\Models\Prodotto\ParteDi;

use Illuminate\Support\Collection;

class ParteDiService
{
    public function __construct()
    {
        //
    }



/**
 * Cerca una relazione parte_di per chiave composta (videogioco + categoria).
 *
 * @param int|null $id1 Il codice del videogioco.
 * @param string|null $id2 Il nome della categoria.
 * @return ParteDi|null Il modello trovato o null.
 * @throws \InvalidArgumentException Se i parametri non sono validi.
 */
public function ricercaPerChiave(?int $id1, ?string $id2): ?ParteDi
{
    if ($id1 === null || $id1 < 0)
        throw new \InvalidArgumentException("id1 non valido o null");
    if ($id2 === null || $id2 === '')
        throw new \InvalidArgumentException("id2 null o vuoto");

    return ParteDi::where('videogioco', $id1)
                  ->where('categoria', $id2)
                  ->first();
}
/**
 * Ritorna una Collection di ParteDi ordinati.
 *
 * @param string|null $ordinamento La colonna su cui applicare l'ordinamento (es. 'videogioco asc').
 * @return Collection<ParteDi>
 * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
 */
public function allElements(?string $ordinamento): Collection
{
    if ($ordinamento == null || $ordinamento == '')
        throw new \InvalidArgumentException("ordinamento vuoto o null");

    switch ($ordinamento)
    {
        case 'videogioco asc':
            $parteDi = ParteDi::orderBy('videogioco', 'asc')->get();
            break;
        case 'videogioco desc':
            $parteDi = ParteDi::orderBy('videogioco', 'desc')->get();
            break;
        case 'categoria asc':
            $parteDi = ParteDi::orderBy('categoria', 'asc')->get();
            break;
        case 'categoria desc':
            $parteDi = ParteDi::orderBy('categoria', 'desc')->get();
            break;
        default:
            throw new \InvalidArgumentException("ordinamento scritto in modo errato");
    }

    return $parteDi;
}

    /**
     * Inserisce una nuova relazione parte_di.
     *
     * @param ParteDi $item Il modello da inserire.
     * @throws \InvalidArgumentException Se il modello è null.
     */
    public function newInsert(ParteDi $item): void
    {
        if ($item === null)
            throw new \InvalidArgumentException("item null");

        $item->save();
    }

}