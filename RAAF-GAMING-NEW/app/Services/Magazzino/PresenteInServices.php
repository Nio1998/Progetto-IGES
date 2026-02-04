<?php

namespace App\Services\Magazzino;

use App\Models\Magazzino\PresenteIn;
use App\Models\Prodotto\Prodotto;
use Illuminate\Support\Collection;
use App\Models\Magazzino\Magazzino;

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
    /**
     * Calcola come distribuire una quantità di prodotto tra i magazzini disponibili.
     * 
     * @param Prodotto|null $prodotto Il prodotto da rifornire oppure null
     * @param int $quantita La quantità totale da distribuire
     * @return \Illuminate\Support\Collection<int, array{magazzino: Magazzino, quantita: int, presente: int}>
     * @throws \InvalidArgumentException Se i parametri non sono validi
     * @throws \Exception Se la somma delle quantità supera la capienza di un magazzino
     */
    public function getMagazziniDaRifornire(?Prodotto $prodotto, int $quantita): Collection
    {
        if($prodotto == null || $prodotto?->codice_prodotto == null || $quantita <= 0)
            throw new \InvalidArgumentException("Precondizione non rispettata");

        $presenteIn = $prodotto->presenteIn()->with('getMagazzino')->get();
        $cont = $quantita;
        $aggiornamenti = collect();
        
        $sommeMagazzini = [];
        
        foreach($presenteIn as $presente)
        {
            if($cont <= 0)
                break;
            
            if(!isset($sommeMagazzini[$presente->magazzino]))
                $sommeMagazzini[$presente->magazzino] = PresenteIn::where('magazzino', $presente->magazzino)->sum('quantita_disponibile');

            $somma = $sommeMagazzini[$presente->magazzino];
            $capienza = $presente->getMagazzino->capienza;

            if($somma > $capienza)
                throw new \Exception("Qualcosa è andato storto");

            $diff = (($somma + $cont) - $capienza);
            $diff = ($diff <= 0) ? 0 : $diff;
            $pago = ($cont - $diff);
            
            if($pago > 0)
                $aggiornamenti->push(['magazzino' => $presente->getMagazzino, 'quantita' => $pago, 'presente' => 1]); 
                
            $cont = $diff;
        }

        if($cont == 0)
            return $aggiornamenti;

        $magazzini = Magazzino::whereDoesntHave('presenteIn', function ($q) use ($prodotto) {$q->where('prodotto', $prodotto->codice_prodotto);})->get();

        foreach($magazzini as $magazzino)
        {
            if($cont <= 0)
                break;
            
            if(!isset($sommeMagazzini[$magazzino->indirizzo]))
                $sommeMagazzini[$magazzino->indirizzo] = PresenteIn::where('magazzino', $magazzino->indirizzo)->sum('quantita_disponibile');

            $somma = $sommeMagazzini[$magazzino->indirizzo];
            $capienza = $magazzino->capienza;

            if($somma > $capienza)
                throw new \Exception("Qualcosa è andato storto");

            $diff = (($somma + $cont) - $capienza);
            $diff = ($diff <= 0) ? 0 : $diff;
            $pago = ($cont - $diff);
            
            if($pago > 0)
                $aggiornamenti->push(['magazzino' => $magazzino, 'quantita' => $pago, 'presente' => 0]); 
                
            $cont = $diff;
        }

        return $cont == 0 ? $aggiornamenti : collect();
    }

    public function getDisponibilita(Prodotto $prodotto, int $quantitaDaAcquistare): Collection
        {
            if ($prodotto == null || $prodotto?->codice_prodotto == null || $prodotto?->codice_prodotto == "") {
                throw new \InvalidArgumentException("Inserire un prodotto valido.");
            }

            if($quantitaDaAcquistare <= 0) {
                throw new \InvalidArgumentException("La quantità da acquistare deve essere un numero positivo.");
            }

            // Collection che conterrà il risultato finale
            // Ogni elemento sarà un array associativo con 'presente_in' e 'quantita'
            $risultato = collect();
            
            // Variabile che tiene traccia di quanta merce dobbiamo ancora trovare
            // Inizialmente uguale alla quantità richiesta, diminuisce man mano che troviamo disponibilità
            $quantitaRimanente = $quantitaDaAcquistare;

            // Query al database per recuperare tutti i magazzini che contengono questo prodotto
            // Condizioni:
            // 1. Il prodotto deve corrispondere (where 'prodotto')
            // 2. La quantità deve essere maggiore di zero (magazzini non vuoti)
            // 3. Ordinamento per quantità decrescente (prima i magazzini più forniti)
            $presenteInList = PresenteIn::where('prodotto', $prodotto->codice_prodotto)
                                        ->where('quantita_disponibile', '>', 0)  // Esclude magazzini vuoti
                                        ->orderBy('quantita_disponibile', 'desc') // Ordina dal più fornito al meno fornito
                                        ->get();

            // Itera su ogni magazzino che contiene il prodotto
            foreach ($presenteInList as $presenteIn) {
                
                // Se abbiamo già soddisfatto la richiesta, usciamo dal ciclo
                // Questo evita iterazioni inutili
                if ($quantitaRimanente <= 0) {
                    break;
                }

                // Calcola quanto possiamo prendere da questo magazzino
                // Usiamo il minimo tra:
                // - La quantità disponibile in questo magazzino ($presenteIn->quantita)
                // - La quantità che ci serve ancora ($quantitaRimanente)
                // 
                // Esempio 1: magazzino ha 100, ci servono 30 -> prendiamo 30
                // Esempio 2: magazzino ha 20, ci servono 30 -> prendiamo 20
                $quantitaDaTogliere = min($presenteIn->quantita_disponibile, $quantitaRimanente);

                // Aggiungi questo magazzino alla Collection
                // Salviamo sia il riferimento al magazzino (PresenteIn) che la quantità da prelevare
                $risultato->push([
                    'presente_in' => $presenteIn,  // Oggetto completo del model PresenteIn
                    'quantita' => $quantitaDaTogliere  // Quantità da prelevare da questo magazzino
                ]);

                // Aggiorna la quantità rimanente da trovare
                // Sottraiamo quanto abbiamo appena "prenotato" da questo magazzino
                $quantitaRimanente -= $quantitaDaTogliere;
            }

            // Restituisce la Collection con tutti i magazzini e le relative quantità da prelevare
            // NOTA: Se $quantitaRimanente > 0 significa che non c'è abbastanza disponibilità totale
            //       ma restituiamo comunque tutto quello che abbiamo trovato
            return $risultato;
        }

    public function quantitaTotaleProdotto(int $codiceProdotto): int
        {       
        return PresenteIn::where('prodotto', $codiceProdotto)->sum('quantita_disponibile');}
        }
