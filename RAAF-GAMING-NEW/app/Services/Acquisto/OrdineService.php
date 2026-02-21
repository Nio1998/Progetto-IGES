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

    /**
     * Restituisce tutti gli ordini non ancora consegnati.
     * Un ordine è considerato non consegnato se il campo gestore è null,
     * ovvero nessun gestore lo ha ancora preso in carico.
     *
     * @return Collection<Ordine> la collezione degli ordini non consegnati
     */
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

    /**
     * Aggiorna un ordine esistente e inserisce la relativa spedizione in modo transazionale.
     * Se uno dei due salvataggi fallisce, l'intera operazione viene annullata.
     *
     * @param Ordine  $ordine  il model dell'ordine da aggiornare
     * @param Spedito $spedito il model della spedizione da inserire
     * @return void
     * @throws \InvalidArgumentException se uno dei due model è null
     * @throws \Exception se uno dei due salvataggi fallisce
     */
    public function doUpdate(?Ordine $ordine, ?Spedito $spedito): void
    {
        if ($ordine === null)
            throw new \InvalidArgumentException("L'ordine è null");

        if ($spedito === null)
            throw new \InvalidArgumentException("Lo spedito è null");

        DB::transaction(function () use ($ordine, $spedito) {
            $ordine->save();

            $spedito->ordine = $ordine->codice;
            $spedito->save();
        });
    }
}
