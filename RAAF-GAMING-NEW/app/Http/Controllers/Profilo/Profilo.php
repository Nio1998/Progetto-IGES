<?php

namespace App\Http\Controllers\Profilo;

use App\Http\Controllers\Controller;
use App\Services\Profilo\CartaDiCreditoService;
use App\Services\Profilo\ClienteService;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class Profilo extends Controller
{
    public function mostraProfilo()
    {
        $clienteService = new ClienteService();
        $cliente = $clienteService->getUtenteAutenticato();

        $data = [
            'cliente' => $cliente,
        ];

        return view('PresentazioneProfilo.profilo', compact('data'));
    }

    public function modificaProfilo(Request $request)
    {
        $response = [
            'password' => false,
            'carta' => null,
            'errorMessage' => null
        ];

        $clienteService = new ClienteService();
        $cliente = $clienteService->getUtenteAutenticato();

        if (!$cliente) {
            return response()->json(['errorMessage' => 'Utente non autenticato'], 401);
        }

        // 1. VALIDAZIONE INPUT
        $validator = Validator::make($request->all(), [
            'passwordNuova' => 'nullable|min:8',
            'cartaNuova'    => 'nullable|size:16',
            'codiceNuovo'   => 'nullable|integer|between:100,999',
            'dataScadNuova' => 'nullable|date|after:today',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            if($errors->has('cartaNuova')) $msg = "Numero carta non valido (16 cifre)";
            elseif($errors->has('codiceNuovo')) $msg = "CVV non valido (3 cifre)";
            elseif($errors->has('dataScadNuova')) $msg = "La carta di credito è scaduta";
            else $msg = "Dati non validi";
            
            return response()->json(['errorMessage' => $msg]);
        }

        $passwordChanged = false;
        $cartaChanged = false;

        if ($request->filled('passwordNuova')) {
            $newMd5 = md5($request->passwordNuova);
            
            if ($newMd5 === $cliente->password) {
                return response()->json(['errorMessage' => "LA PASSWORD COINCIDE CON QUELLO GIA' IN USO"]);
            }

            $cliente->password = $newMd5;
            $passwordChanged = true;
        }

        // Preparo i dati della carta
        $nuovaCartaCodice = null;
        if ($request->filled(['cartaNuova', 'codiceNuovo', 'dataScadNuova'])) {
            $nuovaCartaCodice = $request->cartaNuova;

            if ($nuovaCartaCodice == $cliente->cartadicredito->codicecarta) {
                return response()->json(['errorMessage' => "LA CARTA COINCIDE CON QUELLA GIA' IN USO"]);
            }
            
            $cartaChanged = true;
        }

        try {
            // A. Salvo modifiche utente (Password)
            if ($passwordChanged) {
                $clienteService->doUpdate($cliente);
                $response['password'] = true;
            }

            // B. Salvo modifiche carta
            if ($cartaChanged) {
                
                try {
                    $cartaService = new CartaDiCreditoService();
                    $carta = $cliente->cartadicredito;

                    $vecchiacarta = $cliente->cartadicredito->codicecarta;
                    $carta->codicecarta = $request->cartaNuova ?? $carta->codicecarta;
                    $carta->data_scadenza = $request->dataScadNuova ?? $carta->data_scadenza;
                    $carta->codice_cvv = $request->codiceNuovo ?? $carta->codice_cvv;

                    // Aggiorna carta
                    $cartaService->doUpdate($carta, $vecchiacarta);

                    // Prepara risposta carta mascherata
                    $response['carta'] = "*****" . substr($request->cartaNuova, 12, 16);

                } catch (QueryException $e) {
                    // C. GESTIONE ERRORE SPECIFICA
                    
                    if ($passwordChanged) {
                        // Password salvata nel blocco A, ma Carta fallita nel blocco B
                        return response()->json([
                            'errorMessage' => "Non puoi utilizzare questa carta ma la password e' stata cambiata"
                        ]);
                    } else {
                        // Solo Carta modificata e fallita
                        return response()->json([
                            'errorMessage' => "Non puoi utilizzare questa carta"
                        ]);
                    }
                }
            } else {
                // Se la carta non è cambiata, recupero quella attuale per la risposta 
                $currentCard = $cliente->cartadicredito;
                if(strlen($currentCard) >= 16) {
                    $response['carta'] = "*****" . substr($currentCard, 12, 4);
                }
            }

        } catch (\Exception $e) {
            // Catch generico per errori imprevisti
            return response()->json(['errorMessage' => "Errore del server: " . $e->getMessage()], 500);
        }

        return response()->json($response);
    }
}
