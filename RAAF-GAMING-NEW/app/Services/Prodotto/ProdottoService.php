<?php

namespace App\Services\Prodotto;

use App\Models\Prodotto\Prodotto;
use App\Models\Prodotto\Recensisce;
use App\Models\Prodotto\Videogioco;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ProdottoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Cerca un prodotto tramite il suo codice identificativo con tutte le relazioni caricate.
     *
     * @param string|null $code Il codice prodotto da cercare.
     * @return \App\Models\Prodotto|null Il modello Prodotto trovato con relazioni o null.
     * @throws \InvalidArgumentException Se il codice fornito è null o vuoto.
     */
    public function ricercaPerChiave(?string $code): ?Prodotto
    {
        if($code == null || $code == "")
            throw new \InvalidArgumentException("Codice prodotto null o vuoto");
        
        $prodotto = Prodotto::where('codice_prodotto', $code)->with([
                'presenteIn',
                'abbonamento',
                'console',
                'dlc',
                'videogioco',
                'getFornitore',
                'recensioni'
            ])->first();

        if($prodotto != null) {
            $prodotto->prezzo_effettivo = ($prodotto->sconto > 0) ? $prodotto->prezzo * (1 - $prodotto->sconto / 100) : $prodotto->prezzo;
            $prodotto->disponibile = $prodotto->quantita_fornitura > 0;
            $prodotto->necessita_rifornimento = $prodotto->quantita_fornitura < 10;
        }

        return $prodotto;
    }

    /**
     * Cerca prodotti il cui nome contiene la sottostringa fornita.
     *
     * @param string|null $nome La sottostringa da cercare nel nome del prodotto.
     * @param bool $soloDisponibili Se true, filtra solo prodotti con quantità disponibile.
     * @return \Illuminate\Support\Collection Collection di prodotti con dati calcolati (prezzo effettivo, disponibilità, promozione).
     * @throws \InvalidArgumentException Se il nome fornito è null.
     */
    public function ricercaPerSottostringa(?string $nome): Collection
    {
        if($nome == null)
            throw new \InvalidArgumentException("Nome null");
        
        $query = Prodotto::where('nome', 'LIKE', "%{$nome}%");
        
        // metto in memoria solo i campi necessari
        $prodotti = $query->get(['codice_prodotto', 'nome', 'prezzo', 'sconto', 'data_uscita', 'quantita_fornitura']);
        
        // arricchisce ogni prodotto con dati calcolati
        return $prodotti->map(function($prodotto) {
            // Prezzo effettivo con sconto
            $prodotto->prezzo_effettivo = $prodotto->sconto > 0 
                ? $prodotto->prezzo * (1 - $prodotto->sconto / 100)
                : $prodotto->prezzo;
            
            // Verifica disponibilità
            $prodotto->disponibile = $prodotto->quantita_fornitura > 0;
            
            // Verifica se è in promozione
            $prodotto->in_promozione = $prodotto->sconto > 0;
            
            return $prodotto;
        });
    }

    /**
     * Cerca un prodotto tramite nome esatto per operazioni di rifornimento.
     *
     * @param string|null $nome Il nome esatto del prodotto da cercare.
     * @return \App\Models\Prodotto|null Il modello Prodotto trovato con info rifornimento o null.
     * @throws \InvalidArgumentException Se il nome fornito è null.
     */
    public function ricercaPerNome(?string $nome): ?Prodotto
    {
        if($nome == null)
            throw new \InvalidArgumentException("Nome null");
        
        $prodotto = Prodotto::where('nome', $nome)
            ->with('getFornitore') // Eager loading fornitore necessario per rifornimento
            ->first();
        
        // info utili per rifornimento
        if($prodotto != null) {
            // Verifica se necessita rifornimento
            $prodotto->necessita_rifornimento = $prodotto->quantita_fornitura < 10;
            
            // Calcola giorni dall'ultima fornitura
            $prodotto->giorni_data_fornitura = $prodotto->data_fornitura
                ? now()->diffInDays($prodotto->data_fornitura)
                : null;
        }
        
        return $prodotto;
    }

    /**
     * Ritorna il valore massimo di codice_prodotto presente nella tabella prodotto.
     * Se la tabella è vuota, ritorna 0.
     * @return int Il valore massimo di codice_prodotto, oppure 0 se non ci sono prodotti.
     */
    public function getMax(): int
    {
        return ((Prodotto::max('codice_prodotto') ?? 0) + 1);
    }

    /**
     * Ritorna una Collection di Prodotto (equivalente a ArrayList<ProdottoBean>).
     * @param string|null $ordinamento La colonna e direzione di ordinamento (es. 'prezzo asc').
     * @return \Illuminate\Support\Collection|\App\Models\Prodotto[] Una collezione di oggetti Prodotto.
     * @throws \InvalidArgumentException Se il parametro di ordinamento è null, vuoto o non valido.
     */
    public function allElements(?string $ordinamento): Collection
    {
        if ($ordinamento === null || $ordinamento === '')
            throw new \InvalidArgumentException("ordinamento e' null o stringa vuota");

        switch ($ordinamento) {
            case 'codice_prodotto asc':
                $prodotti = Prodotto::orderBy('codice_prodotto', 'asc')->get();
                break;
            case 'codice_prodotto desc':
                $prodotti = Prodotto::orderBy('codice_prodotto', 'desc')->get();
                break;

            case 'prezzo asc':
                $prodotti = Prodotto::orderBy('prezzo', 'asc')->get();
                break;
            case 'prezzo desc':
                $prodotti = Prodotto::orderBy('prezzo', 'desc')->get();
                break;

            case 'sconto asc':
                $prodotti = Prodotto::orderBy('sconto', 'asc')->get();
                break;
            case 'sconto desc':
                $prodotti = Prodotto::orderBy('sconto', 'desc')->get();
                break;

            case 'data_uscita asc':
                $prodotti = Prodotto::orderBy('data_uscita', 'asc')->get();
                break;
            case 'data_uscita desc':
                $prodotti = Prodotto::orderBy('data_uscita', 'desc')->get();
                break;

            case 'nome asc':
                $prodotti = Prodotto::orderBy('nome', 'asc')->get();
                break;
            case 'nome desc':
                $prodotti = Prodotto::orderBy('nome', 'desc')->get();
                break;

            case 'quantita_fornitura asc':
                $prodotti = Prodotto::orderBy('quantita_fornitura', 'asc')->get();
                break;
            case 'quantita_fornitura desc':
                $prodotti = Prodotto::orderBy('quantita_fornitura', 'desc')->get();
                break;

            case 'data_fornitura asc':
                $prodotti = Prodotto::orderBy('data_fornitura', 'asc')->get();
                break;
            case 'data_fornitura desc':
                $prodotti = Prodotto::orderBy('data_fornitura', 'desc')->get();
                break;

            case 'fornitore asc':
                $prodotti = Prodotto::orderBy('fornitore', 'asc')->get();
                break;
            case 'fornitore desc':
                $prodotti = Prodotto::orderBy('fornitore', 'desc')->get();
                break;

            case 'gestore asc':
                $prodotti = Prodotto::orderBy('gestore', 'asc')->get();
                break;
            case 'gestore desc':
                $prodotti = Prodotto::orderBy('gestore', 'desc')->get();
                break;

            default:
                throw new \InvalidArgumentException("ordinamento scritto in modo errato");
        }

        return $prodotti;
    }

    /**
     * Ritorna i 6 prodotti in evidenza per la homepage, con cache a tempo.
     *
     * I 6 prodotti sono composti da:
     *  - il prodotto con la migliore media recensioni (calcolata in PHP)
     *  - l'ultimo videogioco uscito per data_uscita (escluso il precedente)
     *  - i 4 videogiochi con sconto > 0 (esclusi i precedenti due)
     *
     * I dati in cache contengono solo i campi essenziali per la visualizzazione:
     * codice_prodotto, nome, prezzo, sconto, data_uscita, con i seguenti campi calcolati dinamicamente:
     *  - prezzo_effettivo: prezzo scontato se sconto > 0, altrimenti prezzo base
     *  - disponibile: true se quantita_fornitura > 0
     *  - in_promozione: true se sconto > 0
     *
     * @return \Illuminate\Support\Collection Una collezione di 6 prodotti con i campi essenziali e calcolati.
     * @throws \RuntimeException Se non ci sono recensioni disponibili.
     * @throws \RuntimeException Se non ci sono altri videogiochi disponibili oltre al miglior recensito.
     * @throws \RuntimeException Se non ci sono almeno 4 prodotti scontati disponibili.
     */
    public function getTop6Home(): Collection
    {
        return Cache::remember('top6_home', now()->addMinutes(30), function () {

            // 1. Miglior recensito: solo prodotto e voto, media tutto calcolato in memoria
            $migliorCodice = Recensisce::select('prodotto', 'voto')
                ->get()
                ->groupBy('prodotto')
                ->map(fn($recensioni) => $recensioni->avg('voto'))
                ->sortDesc()
                ->keys()
                ->first();

            if (!$migliorCodice)
                throw new \RuntimeException("Nessuna recensione disponibile");

            // 2. Ultimo uscito: niente eager loading
            $ultimoCodice = Videogioco::select('videogioco.prodotto')
                ->join('prodotto', 'videogioco.prodotto', '=', 'prodotto.codice_prodotto')
                ->where('videogioco.prodotto', '!=', $migliorCodice)
                ->orderByDesc('prodotto.data_uscita')
                ->value('videogioco.prodotto');

            if (!$ultimoCodice)
                throw new \RuntimeException("Nessun altro videogioco disponibile");

            // 3. I 4 più scontati: niente eager loading
            $scontatiCodici = Videogioco::select('videogioco.prodotto')
                ->join('prodotto', 'prodotto.codice_prodotto', '=', 'videogioco.prodotto')
                ->where('prodotto.sconto', '>', 0)
                ->where('videogioco.prodotto', '!=', $migliorCodice)
                ->where('videogioco.prodotto', '!=', $ultimoCodice)
                ->limit(4)
                ->pluck('videogioco.prodotto');

            if ($scontatiCodici->count() < 4)
                throw new \RuntimeException("Non ci sono abbastanza prodotti scontati");

            // 4. Singola query finale con tutti e i 6 codici già in memoria
            $codici = $scontatiCodici->push($migliorCodice)->push($ultimoCodice);

            return Prodotto::whereIn('codice_prodotto', $codici)
                ->get(['codice_prodotto', 'nome', 'prezzo', 'sconto', 'data_uscita', 'quantita_fornitura']) //NON MI SERVONO IN MEMORIA TUTTI I CAMPI
                ->map(function ($prodotto) {

                    $prodotto->prezzo_effettivo = $prodotto->sconto > 0
                        ? round($prodotto->prezzo * (1 - $prodotto->sconto / 100), 2)
                        : $prodotto->prezzo;

                    $prodotto->disponibile   = $prodotto->quantita_fornitura > 0;
                    $prodotto->in_promozione = $prodotto->sconto > 0;

                    unset($prodotto->quantita_fornitura);

                    return $prodotto;
                });
        });
    }
    

    /**
     * Inserisce un nuovo prodotto nel database insieme alla sua specializzazione (Videogioco, Console, DLC o Abbonamento).
     * Il metodo si aspetta giustamente la relazione con la specializzazione
     * 
     * @param Prodotto|null $item Il prodotto da inserire con la relazione alla specializzazione già impostata
     * @return void
     * @throws \InvalidArgumentException Se l'item è null o se non è presente esattamente una specializzazione
     */
    public function newInsert(?Prodotto $item): void
    {
        // Verifica che il prodotto non sia null
        if ($item === null) {
            throw new \InvalidArgumentException("Il prodotto inserito è null");
        }

        // Conta quante relazioni di specializzazione sono presenti
        $contatoreRelazioni = 0;
        
        if ($item->videogioco !== null)
            $contatoreRelazioni++;
        if ($item->console !== null)
            $contatoreRelazioni++;
        if ($item->dlc !== null)
            $contatoreRelazioni++;
        if ($item->abbonamento !== null)
            $contatoreRelazioni++;

        //Verifica che sia presente esattamente una specializzazione
        if($contatoreRelazioni !== 1) {
            throw new \InvalidArgumentException("Il prodotto deve avere esattamente una specializzazione");
        }

        // Utilizza una transazione per garantire l'atomicità dell'operazione
        DB::transaction(function () use ($item) {
            // Salva prima il prodotto padre
            $item->save();

            // Salva la specializzazione collegata
            if ($item->videogioco !== null) {
                $videogioco = $item->videogioco;
                $videogioco->prodotto = $item->codice_prodotto;
                $videogioco->save();
            }

            if ($item->console !== null) {
                $console = $item->console;
                $console->prodotto = $item->codice_prodotto;
                $console->save();
            }
            if ($item->dlc !== null) {
                $dlc = $item->dlc;
                $dlc->prodotto = $item->codice_prodotto;
                $dlc->save();
            }
            if ($item->abbonamento !== null) {
                $abbonamento = $item->abbonamento;
                $abbonamento->prodotto = $item->codice_prodotto;
                $abbonamento->save();
            }
        });

        // Invalida la cache dopo il salvataggio riuscito perché è stato aggiunto un nuovo prodotto 
        // e si deve aggiornare
        Cache::forget('top6_home');
    }

    /**
     * Aggiorna un prodotto esistente insieme alla sua specializzazione (Videogioco, Console, DLC o Abbonamento).
     * Il metodo si aspetta giustamente la relazione con la specializzazione
     * 
     * Dopo l'aggiornamento riuscito, invalida la cache per forzare il refresh dei dati.
     * 
     * @param Prodotto|null $item Il prodotto da aggiornare con la relazione alla specializzazione già impostata
     * @return void
     * @throws \InvalidArgumentException Se l'item è null o se non è presente esattamente una specializzazione
     * @throws \Exception Se il prodotto non esiste nel database
     */
    public function doUpdate(?Prodotto $item): void
    {
        // Verifica che il prodotto non sia null
        if ($item === null) {
            throw new \InvalidArgumentException("Il prodotto inserito è null");
        }

        // Conta quante relazioni di specializzazione sono presenti
        $contatoreRelazioni = 0;
        
        if ($item->videogioco !== null)
            $contatoreRelazioni++;
        if ($item->console !== null)
            $contatoreRelazioni++;
        if ($item->dlc !== null)
            $contatoreRelazioni++;
        if ($item->abbonamento !== null)
            $contatoreRelazioni++;

        // Verifica che sia presente esattamente una specializzazione
        if ($contatoreRelazioni !== 1) {
            throw new \InvalidArgumentException("Il prodotto deve avere esattamente una specializzazione");
        }

        // Utilizza una transazione per garantire l'atomicità dell'operazione
        DB::transaction(function () use ($item) {
            // Aggiorna il prodotto padre
            $item->update();

            // Aggiorna la specializzazione collegata
            if ($item->videogioco !== null) {
                $videogioco = $item->videogioco;
                $videogioco->prodotto = $item->codice_prodotto;
                $videogioco->update();
            }
            if ($item->console !== null) {
                $console = $item->console;
                $console->prodotto = $item->codice_prodotto;
                $console->update();
            }
            if ($item->dlc !== null) {
                $dlc = $item->dlc;
                $dlc->prodotto = $item->codice_prodotto;
                $dlc->update();
            }
            if ($item->abbonamento !== null) {
                $abbonamento = $item->abbonamento;
                $abbonamento->prodotto = $item->codice_prodotto;
                $abbonamento->update();
            }
        });

        // Invalida la cache dopo il salvataggio riuscito perché è stato aggiunto un nuovo prodotto 
        // e si deve aggiornare
        Cache::forget('top6_home');
    }

}
