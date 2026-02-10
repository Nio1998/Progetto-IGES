<?php

namespace App\Services\Prodotto;

use App\Models\Prodotto\Prodotto;
use Illuminate\Support\Collection;

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
                'abbonamenti',
                'console',
                'dlc',
                'videogioco',
                'fornitore',
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
            ->with('fornitore') // Eager loading fornitore necessario per rifornimento
            ->first();
        
        // info utili per rifornimento
        if($prodotto != null) {
            // Verifica se necessita rifornimento
            $prodotto->necessita_rifornimento = $prodotto->quantita_fornitura < 10;
            
            // Calcola giorni dall'ultima fornitura
            $prodotto->giorni_ultima_fornitura = $prodotto->ultima_fornitura
                ? now()->diffInDays($prodotto->ultima_fornitura)
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

}
