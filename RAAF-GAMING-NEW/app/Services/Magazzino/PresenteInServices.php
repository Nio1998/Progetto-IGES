<?php

namespace App\Services\Magazzino;

use App\Models\Magazzino\PresenteIn;

class PresenteInServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    /**
     * Cerca un record PresenteIn basandosi sulla chiave composta fornita.
     *
     * @param int $id1 L'ID del prodotto.
     * @param string|null $id2 Il codice del magazzino.
     * @return \App\Models\PresenteIn|null Il modello PresenteIn trovato o null.
     * @throws \InvalidArgumentException Se i parametri forniti non sono validi.
     */
    public function ricercaPerChiave(int $id1, ?string $id2): ?PresenteIn
    {
        if ($id1 < 0 || $id2 == null || $id2 == "")
            throw new \InvalidArgumentException("id1 negativo e/o id2 è null o id2 è stringa vuota");
        
        $presenteIn = PresenteIn::where('prodotto', $id1)
                                ->where('magazzino', $id2)
                                ->first();
        
        return $presenteIn;
    }
}
