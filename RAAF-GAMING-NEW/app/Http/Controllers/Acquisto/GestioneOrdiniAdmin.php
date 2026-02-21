<?php

namespace App\Http\Controllers\Acquisto;

use App\Http\Controllers\Controller;
use App\Models\Acquisto\Spedito;
use App\Services\Acquisto\CorriereEspressoService;
use App\Services\Acquisto\OrdineService;
use App\Services\Profilo\GestoreService;
use Illuminate\Http\Request;

class GestioneOrdiniAdmin extends Controller
{
    public function homeOrdine(Request $request)
    {
        $ordineService = new OrdineService();
        $corriereService = new CorriereEspressoService();

        $ordiniNonConsegnati = $ordineService->getOrdiniNonConsegnati();
        $corrieri = $corriereService->allElements('nome asc');

        $data = [
            'ordiniNonConsegnati' => $ordiniNonConsegnati,
            'corrieri'            => $corrieri,
        ];

        return view('PresentazioneAcquisto.paginaGestioneOrdini', compact('data'));
    }

    public function formOrdiniAdmin(Request $request)
    {
        $ordineService = new OrdineService();
        $gestoreService = new GestoreService();

        $numeroOrdine = $request->input('numeroOrdine');
        $corriere = $request->input('corriere');
        $dataConsegna = $request->input('consegnaO');

        // Controllo campi vuoti
        if (empty($numeroOrdine) || empty($corriere) || empty($dataConsegna))
            return redirect()->route('homeOrdine')->with('error', 'Errore!');

        // Cerco l'ordine tra quelli non ancora consegnati
        $ordiniNonConsegnati = $ordineService->getOrdiniNonConsegnati();
        $ordine = $ordiniNonConsegnati->firstWhere('codice', $numeroOrdine);

        if ($ordine === null)
            return redirect()->route('homeOrdine')->with('error', 'Ordine non trovato!');

        // Setto il nuovo stato e il gestore che ha gestito l'ordine
        $ordine->stato = 'spedito';
        $ordine->gestore = $gestoreService->getUtenteAutenticato()->email;

        // Creo la spedizione
        $spedito = new Spedito();
        $spedito->corriere_espresso = $corriere;
        $spedito->data_consegna = $dataConsegna;

        try {
            $ordineService->doUpdate($ordine, $spedito);
            return redirect()->back()->with('success', 'Ordine spedito con successo!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Errore durante la spedizione!');
        }
    }
}
