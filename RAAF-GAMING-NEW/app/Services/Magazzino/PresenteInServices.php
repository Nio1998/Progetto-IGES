<?php

namespace App\Services\Magazzino;

use App\Models\Magazzino\PresenteIn;
use App\Models\Prodotto\Prodotto;
use Illuminate\Support\Collection;
use App\Models\Magazzino\Magazzino;

class PresenteInServices
{
    /**
     * Costruttore del service PresenteInServices.
     * 
     * Attualmente non inizializza dipendenze,
     * ma è presente per futura estendibilità.
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
     * @return \App\Models\Magazzino\PresenteIn|null Il modello PresenteIn trovato o null.
     * @throws \InvalidArgumentException Se i parametri forniti non sono validi.
     */
    public function ricercaPerChiave(int $id1, ?string $id2): ?PresenteIn
    {
        if ($id1 <= 0 || $id2 == null || $id2 == "")
            throw new \InvalidArgumentException("id1 negativo e/o id2 è null o id2 è stringa vuota");
        
        return PresenteIn::where('prodotto', $id1)
                         ->where('magazzino', $id2)
                         ->first();
    }

    /**
     * Calcola come distribuire una quantità di prodotto tra i magazzini disponibili.
     * 
     * La funzione tenta prima di rifornire i magazzini che già contengono il prodotto
     * e successivamente quelli che non lo contengono ancora, rispettando la capienza.
     *
     * @param Prodotto|null $prodotto Il prodotto da rifornire.
     * @param int $quantita La quantità totale da distribuire.
     * @return \Illuminate\Support\Collection<int, array{magazzino: Magazzino, quantita: int, presente: int}>
     * @throws \InvalidArgumentException Se i parametri non sono validi.
     * @throws \Exception Se la capienza di un magazzino viene superata.
     */
    public function getMagazziniDaRifornire(?Prodotto $prodotto, int $quantita): Collection
    {
        if($prodotto == null || $prodotto?->codice_prodotto == null || $prodotto?->codice_prodotto == "" || $quantita <= 0)
            throw new \InvalidArgumentException("Precondizione non rispettata");

        $presenteIn = $prodotto->presenteIn()->with('getMagazzino')->get();
        $cont = $quantita;
        $aggiornamenti = collect();
        $sommeMagazzini = [];

        foreach($presenteIn as $presente)
        {
            if($cont <= 0) break;

            if(!isset($sommeMagazzini[$presente->magazzino]))
                $sommeMagazzini[$presente->magazzino] =
                    PresenteIn::where('magazzino', $presente->magazzino)->sum('quantita_disponibile');

            $somma = $sommeMagazzini[$presente->magazzino];
            $capienza = $presente->getMagazzino->capienza;

            if($somma > $capienza)
                throw new \Exception("Qualcosa è andato storto");

            $diff = max(0, ($somma + $cont) - $capienza);
            $pago = $cont - $diff;

            if($pago > 0)
                $aggiornamenti->push([
                    'magazzino' => $presente->getMagazzino,
                    'quantita' => $pago,
                    'presente' => 1
                ]);

            $cont = $diff;
        }

        if($cont == 0) return $aggiornamenti;

        $magazzini = Magazzino::whereDoesntHave('presenteIn', function ($q) use ($prodotto) {
            $q->where('prodotto', $prodotto->codice_prodotto);
        })->get();

        foreach($magazzini as $magazzino)
        {
            if($cont <= 0) break;

            if(!isset($sommeMagazzini[$magazzino->indirizzo]))
                $sommeMagazzini[$magazzino->indirizzo] =
                    PresenteIn::where('magazzino', $magazzino->indirizzo)->sum('quantita_disponibile');

            $somma = $sommeMagazzini[$magazzino->indirizzo];
            $capienza = $magazzino->capienza;

            if($somma > $capienza)
                throw new \Exception("Qualcosa è andato storto");

            $diff = max(0, ($somma + $cont) - $capienza);
            $pago = $cont - $diff;

            if($pago > 0)
                $aggiornamenti->push([
                    'magazzino' => $magazzino,
                    'quantita' => $pago,
                    'presente' => 0
                ]);

            $cont = $diff;
        }

        return $cont == 0 ? $aggiornamenti : collect();
    }

    /**
     * Calcola la disponibilità di un prodotto nei vari magazzini
     * per soddisfare una richiesta di acquisto.
     *
     * I magazzini vengono considerati in ordine di quantità disponibile
     * decrescente, fino a coprire la quantità richiesta.
     *
     * @param Prodotto $prodotto Il prodotto da acquistare.
     * @param int $quantitaDaAcquistare La quantità richiesta.
     * @return \Illuminate\Support\Collection<int, array{presente_in: PresenteIn, quantita: int}>
     * @throws \InvalidArgumentException Se i parametri non sono validi.
     */
    public function getDisponibilita(?Prodotto $prodotto, int $quantitaDaAcquistare): Collection
    {
        if ($prodotto == null || $prodotto?->codice_prodotto == null || $prodotto?->codice_prodotto == "")
            throw new \InvalidArgumentException("Inserire un prodotto valido.");

        if($quantitaDaAcquistare <= 0)
            throw new \InvalidArgumentException("La quantità da acquistare deve essere un numero positivo.");

        $risultato = collect();
        $quantitaRimanente = $quantitaDaAcquistare;

        $presenteInList = PresenteIn::where('prodotto', $prodotto->codice_prodotto)
                                    ->where('quantita_disponibile', '>', 0)
                                    ->orderBy('quantita_disponibile', 'desc')
                                    ->get();

        foreach ($presenteInList as $presenteIn)
        {
            if ($quantitaRimanente <= 0) break;

            $quantitaDaTogliere = min($presenteIn->quantita_disponibile, $quantitaRimanente);

            $risultato->push([
                'presente_in' => $presenteIn,
                'quantita' => $quantitaDaTogliere
            ]);

            $quantitaRimanente -= $quantitaDaTogliere;
        }

        return $risultato;
    }

    /**
     * Calcola la quantità totale disponibile di un prodotto
     * sommando le giacenze di tutti i magazzini.
     *
     * @param int $codiceProdotto Il codice del prodotto.
     * @return int La quantità totale disponibile.
     */
    public function quantitaTotaleProdotto(int $codiceProdotto): int
    {
        return PresenteIn::where('prodotto', $codiceProdotto)
                         ->sum('quantita_disponibile');
    }

    /**
     * Inserisce un nuovo record PresenteIn nel database.
     *
     * @param PresenteIn $item L'oggetto PresenteIn da inserire.
     * @return void
     * @throws \InvalidArgumentException Se l'item fornito è null.
     */
    public function newInsert(?PresenteIn $item): void
    {
        if($item == null)
            throw new \InvalidArgumentException("Inserito un item null");

        $item->save();
    }

    /**
     * Aggiorna un record PresenteIn esistente nel database,
     * tipicamente utilizzato per operazioni di rifornimento.
     *
     * @param PresenteIn $item Il record PresenteIn da aggiornare.
     * @return void
     * @throws \InvalidArgumentException Se l'item fornito è null.
     */
    public function rifornitura(?PresenteIn $item): void
    {
        if ($item === null)
            throw new \InvalidArgumentException("Inserito un item null");

        PresenteIn::where('prodotto',$item->prodotto)->where('magazzino',$item->magazzino)->update(['quantita_disponibile' => $item->quantita_disponibile]);
    }

    /**
     * Decrementa la quantità disponibile di un prodotto in un magazzino.
     * @param PresenteIn|null $item L'oggetto PresenteIn contenente prodotto e magazzino.
     * Non deve essere null.
     * @param int $quantita La quantità da decrementare.
     * @throws \InvalidArgumentException Se l'oggetto item è null, se la quantità è minore di 0 
     * o maggiore della quantità disponibile.
     */
    public function doUpdate(?PresenteIn $item, int $quantita): void
    {
        if($item == null)
            throw new \InvalidArgumentException("Inserito un item null");

        if($quantita <= 0)
            throw new \InvalidArgumentException("La quantità non può essere negativa");

        $presenteIn = PresenteIn::where('prodotto', $item->prodotto)->where('magazzino', $item->magazzino)->first();

        if($presenteIn == null)
            throw new \InvalidArgumentException("Prodotto non presente nel magazzino specificato");

        if($quantita > $presenteIn->quantita_disponibile)
            throw new \InvalidArgumentException("Quantità richiesta maggiore della quantità disponibile");

        PresenteIn::where('prodotto', $item->prodotto)->where('magazzino', $item->magazzino)->decrement('quantita_disponibile', $quantita);
    }

    /**
     * Ritorna una Collection di PresenteIn (equivalente a ArrayList<PresenteInBean>).
     * @param string $ordinamento La colonna e direzione di ordinamento (es. 'magazzino asc').
     * @return \Illuminate\Support\Collection|\App\Models\PresenteIn[] Una collezione di oggetti PresenteIn.
     * @throws \InvalidArgumentException Se il parametro di ordinamento non Ã¨ valido.
     */
    public function allElements(?string $ordinamento): Collection
    {
        if($ordinamento == null || $ordinamento == "")
            throw new \InvalidArgumentException("ordinamento vuoto o null");

        switch($ordinamento)
        {
            case 'magazzino asc':
                $presenteIn = PresenteIn::orderBy('magazzino', 'asc')->get();
                break;
            case 'magazzino desc':
                $presenteIn = PresenteIn::orderBy('magazzino', 'desc')->get();
                break;
            case 'prodotto asc':
                $presenteIn = PresenteIn::orderBy('prodotto', 'asc')->get();
                break;
            case 'prodotto desc':
                $presenteIn = PresenteIn::orderBy('prodotto', 'desc')->get();
                break;
            case 'quantita_disponibile asc':
                $presenteIn = PresenteIn::orderBy('quantita_disponibile', 'asc')->get();
                break;
            case 'quantita_disponibile desc':
                $presenteIn = PresenteIn::orderBy('quantita_disponibile', 'desc')->get();
                break;
            default:
                throw new \InvalidArgumentException("ordinamento non valido");
        }

        return $presenteIn;
    }
}
