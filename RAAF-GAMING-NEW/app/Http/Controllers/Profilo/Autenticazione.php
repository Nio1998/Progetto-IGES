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
        //dd($request->all()); // Debug: mostra tutti i dati della richiesta
        $clienteService = new ClienteService();
        $email = $request->input('email');
        $password = $request->input('password');

        // Se email o password mancanti → ritorna al form
        if (!$email || !$password) {
            return view('PresentazioneProfilo.login', [
                'message' => 'Inserisci email e password',
                'visita' => ''
            ]);
        }

        // Trova utente
       $utente = $clienteService->ricercaPerChiave($email);
        //dd($utente);
        if (!$utente) {
            return view('PresentazioneProfilo.login', [
                'message' => '',
                'visita' => ''
            ]);
        }

        // Controllo password
        if (!$clienteService->checkPassword($password,$utente)) {
            return view('PresentazioneProfilo.login', [
                'message' => '',
                'visita' => ''
            ]);
        }
        
        return redirect()->route('home'); // route home/index
    }

    public function registrazione(){
        return view('PresentazioneProfilo.registrazione');
    }

   public function registrazioneStore(Request $request)
    {
        // Ottieni i dati dal form
        $nome = $request->input('nome');
        $cognome = $request->input('cognome');
        $email = $request->input('email');
        $codicecarta = $request->input('codicecarta');
        $data_scadenza = $request->input('data_scadenza');
        $codice_cvv = $request->input('codice_cvv');
        $data = $request->input('data');
        $password = $request->input('password');
        
        
        // Validazione dei campi
        if (empty($password) || empty($nome) || empty($cognome) || 
            empty($email) || empty($data) || empty($codicecarta) || 
            strlen($codicecarta) != 16 || empty($codice_cvv) || 
            empty($data_scadenza)) {
            
            Log::warning('Validazione fallita - campi mancanti o non validi');
            Log::warning('Dettagli validazione:', [
                'password_empty' => empty($password),
                'nome_empty' => empty($nome),
                'cognome_empty' => empty($cognome),
                'email_empty' => empty($email),
                'data_empty' => empty($data),
                'codicecarta_empty' => empty($codicecarta),
                'codicecarta_length' => strlen($codicecarta ?? ''),
                'codice_cvv_empty' => empty($codice_cvv),
                'data_scadenza_empty' => empty($data_scadenza)
            ]);
            
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Compila tutti i campi correttamente',
                'visitato' => ''
            ]);
        }
        
        
        // Verifica se il cliente è già registrato
        $clienteService = new ClienteService();
        $clienteEsistente = $clienteService->ricercaPerChiave($email,false);
        
        if ($clienteEsistente) {
            Log::warning('Cliente già registrato con email: ' . $email);
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Sei già iscritto al nostro sito!',
                'visitato' => ''
            ]);
        }
        
        try {
            
            // 1. Inserisci la carta di credito
            $cartaCreditoService = new CartaDiCreditoService();  
            $cartaCredito = new CartaDiCredito();
            $cartaCredito->codicecarta = $codicecarta;
            $cartaCredito->data_scadenza = $data_scadenza;
            $cartaCredito->codice_cvv = $codice_cvv;
            
            
            try {
                $cartaCreditoService->newInsert($cartaCredito);
            } catch (\Exception $e) {
                Log::error('Errore salvataggio carta di credito: ' . $e->getMessage());
                Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
                
                return view('profilo.registrazione', [
                    'message' => 'Non puoi registrarti con questa carta',
                    'visitato' => ''
                ]);
            }
            
            // 2. Inserisci la carta fedeltà
            $cartaFedeltaService = new CartaFedeltaService();
            $cartaFedelta = new CartaFedelta();
            // 3. Genera un codice univoco per la carta fedeltà
            $codiceFedelta = $cartaFedeltaService->generaCodiceFedelta();
            $cartaFedelta->codice = $codiceFedelta;
            $cartaFedelta->punti = 0;
            
            try {
               $cartaFedeltaService->newInsert($cartaFedelta);
            } catch (\Exception $e) {
                Log::error('Errore salvataggio carta fedeltà: ' . $e->getMessage());
                Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
                throw $e;
            }
            
            // 4. Crea il nuovo cliente
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
                $clienteService->newInsert($nuovoCliente, $cartaCredito, $cartaFedelta);
            } catch (\Exception $e) {
                Log::error('Errore salvataggio cliente: ' . $e->getMessage());
                Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
                throw $e;
            }
            
            // Redirect al login con messaggio di successo
            return redirect()->route('loginFirst')
                ->with('success', 'Registrazione completata con successo!');
            
        } catch (\Exception $e) {
            Log::error('=== ERRORE GENERALE REGISTRAZIONE ===');
            Log::error('Messaggio errore: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Linea: ' . $e->getLine());
            Log::error('Stack trace completo:', ['trace' => $e->getTraceAsString()]);
            
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
        return redirect()->route('login');
    }
}
