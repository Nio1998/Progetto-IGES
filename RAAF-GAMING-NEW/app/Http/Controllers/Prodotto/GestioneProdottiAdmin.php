<?php

namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use App\Models\Magazzino\PresenteIn;
use App\Models\Prodotto\Abbonamento;
use App\Models\Prodotto\Console;
use App\Models\Prodotto\Dlc;
use App\Models\Prodotto\ParteDi;
use App\Models\Prodotto\Prodotto;
use App\Models\Prodotto\Videogioco;
use App\Services\Magazzino\PresenteInServices;
use App\Services\Prodotto\CategoriaService;
use App\Services\Prodotto\FornitoreService;
use App\Services\Prodotto\ParteDiService;
use App\Services\Prodotto\ProdottoService;
use App\Services\Prodotto\SoftwareHouseService;
use App\Services\Profilo\GestoreService;
use Illuminate\Http\Request;

class GestioneProdottiAdmin extends Controller
{
    public function homeProdotto(Request $request)
    {
        $prodottoService = new ProdottoService();
        $presenteInService = new PresenteInServices();
        $prodotti = $prodottoService->allElements('codice_prodotto asc');

        foreach($prodotti as $prodotto)
            $prodotto->quantita_disponibile = $presenteInService->quantitaTotaleProdotto($prodotto->codice_prodotto);

        $fornitoreService = new FornitoreService();
        $fornitori = $fornitoreService->allElements("nome asc");

        $categoriaService = new CategoriaService();
        $categorie = $categoriaService->allElements("nome asc");

        $sfhService = new SoftwareHouseService();
        $softwarehouses = $sfhService->allElements("nomesfh asc");

        $data = [
            'prodotti'       => $prodotti,
            'fornitori'      => $fornitori,
            'categorie'      => $categorie,
            'softwarehouses' => $softwarehouses,
        ];

        return view('PresentazioneProdotto.paginaAmministratore',compact('data'));
    }

    public function formProdNuovoAdmin(Request $request)
    {
        $prodottoService = new ProdottoService();
        $parteDiService = new ParteDiService();
        $presenteInService = new PresenteInServices();
        $gestoreService = new GestoreService();

         // Validazione
        $request->validate([
            'nomeP' => 'required|string|max:255',
            'prezzoP' => 'required|numeric|min:0',
            'scontoP' => 'required|integer|min:0|max:100',
            'dataP' => 'required|date',
            'fornitoreP' => 'required|string|max:255',
            'quantitaP' => 'required|integer|min:1',
            'copertina' => 'required|file|max:10240',
            'sceltaP' => 'required|in:videogioco fisico,videogioco digitale,console,dlc,abbonamento',
        ]);

        // Validazione manuale del MIME type
        if ($request->hasFile('copertina')) {
            $file = $request->file('copertina');
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/jpg', 'image/avif'];
            if (!in_array($file->getMimeType(), $allowedMimes)) {
                return redirect()->back()
                    ->with('error', 'Il file deve essere un\'immagine (jpg, png, gif, webp)')
                    ->withInput();
            }
        }

        $gestore = $gestoreService->getUtenteAutenticato();
        $nomeProd = $request->input('nomeP');
        $tipo = $request->input('sceltaP');
        $quantitaProd = (int) $request->input('quantitaP');

        // Verifica se prodotto esiste già
        if ($prodottoService->ricercaPerNome($nomeProd) !== null) {
            return redirect()->back()->with('error', 'Prodotto già esistente');
        }

        // Crea prodotto temporaneo per trovare magazzini disponibili
        $prodottoTemp = new Prodotto();
        $prodottoTemp->codice_prodotto = $prodottoService->getMax();
        
        $magazziniDisponibili = $presenteInService->getMagazziniDaRifornire($prodottoTemp, $quantitaProd);
        
        if ($magazziniDisponibili->isEmpty()) {
            return redirect()->back()->with('error', 'Capienza non disponibile');
        }

        // Crea prodotto di base
        $prodotto = new Prodotto();
        $prodotto->codice_prodotto = $prodottoService->getMax();
        $prodotto->nome = $nomeProd;
        $prodotto->prezzo = (float) $request->input('prezzoP');
        $prodotto->sconto = (int) $request->input('scontoP');
        $prodotto->data_uscita = $request->input('dataP');
        $prodotto->fornitore = $request->input('fornitoreP');
        $prodotto->quantita_fornitura = $quantitaProd;
        $prodotto->data_fornitura = now()->toDateString();
        $prodotto->gestore = $gestore->email;
        $prodotto->copertina = fopen($request->file('copertina')->getRealPath(), 'rb');

        // Crea specializzazione
        if ($tipo === 'videogioco fisico') {
            $videogioco = new Videogioco();
            $videogioco->prodotto = $prodotto->codice_prodotto;
            $videogioco->dimensione = (float) $request->input('dimensioni');
            $videogioco->pegi = (int) $request->input('pegi');
            $videogioco->software_house = $request->input('nomesfh');
            $videogioco->edizione_limitata = filter_var($request->input('limitata'), FILTER_VALIDATE_BOOLEAN);
            $videogioco->ncd = (int) $request->input('ncd');
            $videogioco->vkey = null;

            $prodotto->setRelation('videogioco', $videogioco);
            $prodottoService->newInsert($prodotto);

            $parteDi = new ParteDi();
            $parteDi->videogioco = $prodotto->codice_prodotto;
            $parteDi->categoria = $request->input('categoria');
            $parteDiService->newInsert($parteDi);

        } elseif ($tipo === 'videogioco digitale') {
            $videogioco = new Videogioco();
            $videogioco->prodotto = $prodotto->codice_prodotto;
            $videogioco->dimensione = (float) $request->input('dimensioni');
            $videogioco->pegi = (int) $request->input('pegi');
            $videogioco->software_house = $request->input('nomesfh');
            $videogioco->edizione_limitata = filter_var($request->input('limitata'), FILTER_VALIDATE_BOOLEAN);
            $videogioco->vkey = $request->input('chiave');
            $videogioco->ncd = 0;

            $prodotto->setRelation('videogioco', $videogioco);
            $prodottoService->newInsert($prodotto);

            $parteDi = new ParteDi();
            $parteDi->videogioco = $prodotto->codice_prodotto;
            $parteDi->categoria = $request->input('categoria');
            $parteDiService->newInsert($parteDi);

        } elseif ($tipo === 'console') {
            $console = new Console();
            $console->prodotto = $prodotto->codice_prodotto;
            $console->specifica = $request->input('specifiche');
            $console->colore = $request->input('colore');

            $prodotto->setRelation('console', $console);
            $prodottoService->newInsert($prodotto);

        } elseif ($tipo === 'dlc') {
            $dlc = new Dlc();
            $dlc->prodotto = $prodotto->codice_prodotto;
            $dlc->descrizione = $request->input('descrizione');
            $dlc->dimensione = (int) $request->input('dimensioneDlc');

            $prodotto->setRelation('dlc', $dlc);
            $prodottoService->newInsert($prodotto);

        } elseif ($tipo === 'abbonamento') {
            $abbonamento = new Abbonamento();
            $abbonamento->prodotto = $prodotto->codice_prodotto;
            $abbonamento->codice = $request->input('codice');
            $abbonamento->durata_abbonamento = (int) $request->input('durata');

            $prodotto->setRelation('abbonamento', $abbonamento);
            $prodottoService->newInsert($prodotto);
        }

        // Crea PresenteIn per ogni magazzino (può essere distribuito su più magazzini)
        foreach ($magazziniDisponibili as $magazzinoInfo) {
            $presenteIn = new PresenteIn();
            $presenteIn->prodotto = $prodotto->codice_prodotto;
            $presenteIn->magazzino = $magazzinoInfo['magazzino']->indirizzo;
            $presenteIn->quantita_disponibile = $magazzinoInfo['quantita'];
            $presenteInService->newInsert($presenteIn);
        }

        return redirect()->back()->with('success', 'Prodotto inserito con successo!');
    }

    public function formProdEsistentiAdmin(Request $request)
    {
        $prodottoService = new ProdottoService();
        $presenteInService = new PresenteInServices();

        // Validazione
        $codiceProdotto = $request->input('prod');
        $quantitaDaRifornire = (int) $request->input('quantita');

        // Controllo quantità valida (come Java)
        if ($quantitaDaRifornire <= 0) {
            return redirect()->back()->with('error', 'Quantita\' non valida');
        }

        // Recupera il prodotto
        $daRifornire = $prodottoService->ricercaPerChiave($codiceProdotto);

        if ($daRifornire == null) {
            return redirect()->back()->with('error', 'Il prodotto che vuoi rifornire non esiste');
        }

        // trova lo spazio disponibile
        $magazziniDisponibili = $presenteInService->getMagazziniDaRifornire($daRifornire, $quantitaDaRifornire);

        if ($magazziniDisponibili->isEmpty()) {
            return redirect()->back()->with('error', 'Capienza non disponibile');
        }

        // Distribuisci nei magazzini
        foreach ($magazziniDisponibili as $magazzinoInfo) {
            if ($magazzinoInfo['presente'] == 1) {
                // Magazzino che già ha il prodotto - usa rifornitura
                $presenteIn = $presenteInService->ricercaPerChiave($daRifornire->codice_prodotto, $magazzinoInfo['magazzino']->indirizzo);
                
                if ($presenteIn) {
                    $presenteIn->quantita_disponibile += $magazzinoInfo['quantita'];
                    $presenteInService->rifornitura($presenteIn);
                }
            } else {
                // Nuovo magazzino - crea nuovo record usando service
                $nuovoPresente = new PresenteIn();
                $nuovoPresente->magazzino = $magazzinoInfo['magazzino']->indirizzo;
                $nuovoPresente->prodotto = $daRifornire->codice_prodotto;
                $nuovoPresente->quantita_disponibile = $magazzinoInfo['quantita'];
                $presenteInService->newInsert($nuovoPresente);
            }
        }

        return redirect()->back()->with('success', 'Prodotto Rifornito con Successo');
    }

}
