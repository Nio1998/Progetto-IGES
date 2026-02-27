<?php

namespace App\Http\Controllers\Profilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profilo\Cliente;
use App\Models\Profilo\CartaDiCredito;
use App\Models\Profilo\CartaFedelta;
use App\Services\Profilo\CartaFedeltaService;
use App\Services\Profilo\CartaDiCreditoService;
use App\Services\Profilo\ClienteService;
use Illuminate\Support\Facades\Log;

class Autenticazione extends Controller
{
    public function loginFirst(){
        return view('PresentazioneProfilo.login');
    }

    public function login(Request $request)
    {
        $clienteService = new ClienteService();
        $email = $request->input('email');
        $password = $request->input('password');

        // 1. Controllo email o password mancanti
        if (!$email || !$password) {
            return back()->withErrors(['messaggio' => 'Inserisci email e password']);
        }

        // 2. Trova utente
        $utente = $clienteService->ricercaPerChiave($email);
        
        if (!$utente) {
            // L'utente non esiste: torniamo indietro con l'errore che la tua View aspetta
            return back()->with('error', 'Email/Password errata!')
                ->withInput();
        }

        // 3. Controllo password
        if (!$clienteService->checkPassword($password, $utente)) {
            $clienteService->logoutUtente(); // Assicurati di pulire la sessione se c'è un tentativo di login fallito
            // Password errata: torniamo indietro
            // withInput() serve a non far cancellare l'email che l'utente ha già scritto
            return back()->with('error', 'Email/Password errata!')
                ->withInput();
        }

        // 4. Se arriviamo qui, i dati sono giusti. 
        // RICORDA: devi loggare l'utente in sessione, altrimenti al prossimo click risulterà ospite!
        // Auth::login($utente); 

        return redirect()->route('home');
    }

    public function registrazione(){
        return view('PresentazioneProfilo.registrazione');
    }

   public function registrazioneStore(Request $request)
    {
        $nome = $request->input('nome');
        $cognome = $request->input('cognome');
        $email = $request->input('email');
        $codicecarta = $request->input('codicecarta');
        $data_scadenza = $request->input('data_scadenza');
        $codice_cvv = $request->input('codice_cvv');
        $data = $request->input('data');
        $password = $request->input('password');

        // Validazione nome
        if (empty($nome)) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Hai inserito un nome non valido',
                'visitato' => ''
            ]);
        }

        // Validazione cognome
        if (empty($cognome)) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Hai inserito un cognome non valido',
                'visitato' => ''
            ]);
        }

        // Validazione data di nascita (età minima 18 anni)
        if (empty($data)) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Non hai l\'età per registrarti',
                'visitato' => ''
            ]);
        }
        $dataNascita = new \DateTime($data);
        $oggi = new \DateTime();
        $eta = $oggi->diff($dataNascita)->y;
        if ($eta < 18) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Non hai l\'età per registrarti',
                'visitato' => ''
            ]);
        }

        // Validazione carta (16 cifre)
        if (empty($codicecarta) || strlen($codicecarta) != 16 || !ctype_digit($codicecarta)) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Hai inserito un codice carta non valida',
                'visitato' => ''
            ]);
        }
        
        // Validazione data scadenza carta
        if (empty($data_scadenza)){
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Data scadenza non valida',
                'visitato' => ''
            ]);
        }

        $dataScadenza = \DateTime::createFromFormat('Y-m-d', $data_scadenza);
        if (!$dataScadenza || $dataScadenza < new \DateTime()) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Data scadenza non valida',
                'visitato' => ''
            ]);
        }

        // Validazione CVV (3 cifre)
        if (empty($codice_cvv) || strlen($codice_cvv) != 3 || !ctype_digit($codice_cvv)) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Hai inserito un CVV non valido',
                'visitato' => ''
            ]);
        }

        // Validazione email
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Hai inserito un\'Email non valida',
                'visitato' => ''
            ]);
        }

        // Validazione password
        if (empty($password)) {
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Hai inserito una password non valida',
                'visitato' => ''
            ]);
        }

        // Verifica se il cliente è già registrato
        $clienteService = new ClienteService();
        $clienteEsistente = $clienteService->ricercaPerChiave($email, false);

        if ($clienteEsistente) {
            Log::warning('Cliente già registrato con email: ' . $email);
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Sei già iscritto al nostro sito!',
                'visitato' => ''
            ]);
        }

        try {

            $cartaCreditoService = new CartaDiCreditoService();
            $cartaCredito = new CartaDiCredito();
            $cartaCredito->codicecarta = $codicecarta;
            $cartaCredito->data_scadenza = $data_scadenza;
            $cartaCredito->codice_cvv = $codice_cvv;

            try {
                $cartaCreditoService->newInsert($cartaCredito);
            } catch (\Exception $e) {
                Log::error('Errore salvataggio carta di credito: ' . $e->getMessage());
                return view('PresentazioneProfilo.registrazione', [
                    'message' => 'Non puoi registrarti con questa carta',
                    'visitato' => ''
                ]);
            }

            $cartaFedeltaService = new CartaFedeltaService();
            $cartaFedelta = new CartaFedelta();
            $codiceFedelta = $cartaFedeltaService->generaCodiceFedelta();
            $cartaFedelta->codice = $codiceFedelta;
            $cartaFedelta->punti = 0;

            try {
                $cartaFedeltaService->newInsert($cartaFedelta);
            } catch (\Exception $e) {
                Log::error('Errore salvataggio carta fedeltà: ' . $e->getMessage());
                throw $e;
            }

            $clienteService = new ClienteService();
            $nuovoCliente = new Cliente();
            $nuovoCliente->nome = $nome;
            $nuovoCliente->cognome = $cognome;
            $nuovoCliente->email = $email;
            $nuovoCliente->cartadicredito = $codicecarta;
            $nuovoCliente->data_di_nascita = $data;
            $nuovoCliente->password = $clienteService->getCryptedPassword($password);
            $nuovoCliente->carta_fedelta = $codiceFedelta;

            try {
                $clienteService->newInsert($nuovoCliente, $cartaFedelta, $cartaCredito);
            } catch (\Exception $e) {
                Log::error('Errore salvataggio cliente: ' . $e->getMessage());
                throw $e;
            }

            return redirect()->route('loginFirst')
                ->with('success', 'Registrazione completata con successo!');

        } catch (\Exception $e) {
            Log::error('=== ERRORE GENERALE REGISTRAZIONE ===');
            Log::error('Messaggio errore: ' . $e->getMessage());

            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Errore durante la registrazione: ' . $e->getMessage(),
                'visitato' => ''
            ]);
        }
    }

    public function logout()
    {
        // Ottengo la sessione
        $clienteService = new ClienteService();
        $utente = $clienteService->getUtenteAutenticato();
        // Se non esiste 'utente' (utente non loggato)
        if ($utente === null) {
            return redirect()->route('home');
        }
        // Altrimenti rimuovo gli attributi della sessione
        $clienteService->logoutUtente();
        // Redirect alla home
        return redirect()->route('home');
    }
}
