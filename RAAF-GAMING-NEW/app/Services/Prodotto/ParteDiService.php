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
 * Cerca le relazioni parte_di filtrate per categoria e opzionalmente per videogioco.
 * Se viene passato solo $categoria restituisce tutte le relazioni per quella categoria.
 * Se viene passato anche $videogioco restituisce la relazione specifica per quel videogioco e quella categoria.
 *
 * @param string $categoria Il nome della categoria per cui filtrare.
 * @param int|null $videogioco Il codice del videogioco (opzionale).
 * @return Collection<ParteDi> Una collezione di oggetti ParteDi filtrati.
 * @throws \InvalidArgumentException Se la categoria fornita non è valida.
 */
public function ricercaParteDi(string $categoria, ?int $videogioco = null): Collection
{
    // Controlla che la categoria fornita sia tra quelle ammesse
    $categorieValide = ['Azione', 'Avventura', 'Battle Royale', 'Sport', 'Survival horror'];
    if (!in_array($categoria, $categorieValide)) {
        throw new \InvalidArgumentException("Categoria non valida: {$categoria}");
    }

    // Filtra le relazioni parte_di per la categoria specificata
    $query = ParteDi::where('categoria', $categoria);

    // Se viene passato un videogioco, restringe la ricerca a quel videogioco specifico
    if ($videogioco !== null) {
        $query->where('videogioco', $videogioco);
    }

    return $query->get();
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