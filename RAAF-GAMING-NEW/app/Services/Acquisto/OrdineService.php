<?php

namespace App\Services\Acquisto;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Acquisto\Riguarda;
use App\Models\Acquisto\Spedito;
use App\Models\Acquisto\Ordine;

class OrdineService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getOrdiniNonConsegnati(): Collection
    {
        return Ordine::whereNull('gestore')->get();
    }

    /**
     * Genera un codice univoco per un nuovo ordine.
     * Il codice è un numero casuale di 9 cifre (da 100000000 a 999999999).
     * La generazione viene ripetuta finché non si ottiene un codice
     * non ancora presente nella tabella degli ordini.
     *
     * @return string il codice univoco generato
     */
    public function generaCodiceOrdine(): string 
    {
        do {
            $codice = (string) rand(100000000, 999999999);
            $exists = Ordine::where('codice', $codice)->exists();
        } while ($exists);
        
        return $codice;
    }

    /**
     * Inserisce un nuovo ordine in modo transazionale.
     * Salva l'ordine, la spedizione e i prodotti acquistati.
     * Se uno dei tre inserimenti fallisce, l'intera operazione viene annullata.
     *
     * @param Ordine  $ordine  il model dell'ordine da salvare
     * @param Spedito $spedito il model della spedizione da salvare
     * @param Riguarda $riguarda il model del prodotto acquistato da salvare
     * @return void
     * @throws \Exception se uno degli inserimenti fallisce
     */
    public function newInsert(Ordine $ordine, Spedito $spedito, Riguarda $riguarda): void
    {
        DB::transaction(function () use ($ordine, $spedito, $riguarda) {
            $ordine->save();

            $spedito->ordine = $ordine->codice;
            $spedito->save();

            $riguarda->ordine = $ordine->codice;
            $riguarda->save();
        });
    }

    public function doUpdate(?Ordine $item): void
    {
        if ($item === null)
            throw new \InvalidArgumentException("L'item è null");

        $item->save();
    }
}
