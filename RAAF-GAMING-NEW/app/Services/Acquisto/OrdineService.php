<?php

namespace App\Services\Acquisto;

use App\Models\Acquisto\Ordine;
use Illuminate\Support\Collection;

class OrdineService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function doUpdate(Ordine $item): void
    {
        if ($item === null)
            throw new \InvalidArgumentException("L'item è null");

        $item->save();
    }

    public function getOrdiniNonConsegnati(): Collection
    {
        return Ordine::whereNull('gestore')->get();
    }
}
