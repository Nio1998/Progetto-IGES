<?php

namespace App\Http\Controllers\Profilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Profilo\Cliente;
use App\Models\Profilo\CartaDiCredito;
use App\Models\Profilo\CartaFedelta;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Autenticazione extends Controller
{
    public function loginFirst(Request $request){
        return view('PresentazioneProfilo.login');
    }

    public function login(Request $request)
    {
        //dd($request->all()); // Debug: mostra tutti i dati della richiesta
        
        $email = $request->input('email');
        $password = $request->input('password');

        // Se email o password mancanti → ritorna al form
        if (!$email || !$password) {
            return view('profilo.login', [
                'message' => 'Inserisci email e password',
                'visita' => ''
            ]);
        }

        // Trova utente
        $utente = Cliente::where('email', $email)->first();
        //dd($utente);
        if (!$utente) {
            return view('profilo.login', [
                'message' => 'Email non registrata',
                'visita' => ''
            ]);
        }

        // Controllo password (MD5 per compatibilità)
        if (md5($password) !== $utente->password) {
            return view('profilo.login', [
                'message' => 'Password errata',
                'visita' => ''
            ]);
        }

        // Login corretto → salva sessione
        $request->session()->put('log', true);
        $request->session()->put('emailSession', $email);

        return redirect()->route('home'); // route home/index
    }

    public function registrazione(Request $request){
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
            
            return view('profilo.registrazione', [
                'message' => 'Compila tutti i campi correttamente',
                'visitato' => ''
            ]);
        }
        
        
        // Verifica se il cliente è già registrato
        $clienteEsistente = Cliente::where('email', $email)->first();
        
        if ($clienteEsistente) {
            Log::warning('Cliente già registrato con email: ' . $email);
            return view('PresentazioneProfilo.registrazione', [
                'message' => 'Sei già iscritto al nostro sito!',
                'visitato' => ''
            ]);
        }
        
        
        // Usa una transaction per garantire consistenza dei dati
        try {
            DB::beginTransaction();
            
            // 1. Inserisci la carta di credito
            $cartaCredito = new CartaDiCredito();
            $cartaCredito->codicecarta = $codicecarta;
            $cartaCredito->data_scadenza = $data_scadenza;
            $cartaCredito->codice_cvv = $codice_cvv;
            
            
            try {
                $cartaCredito->save();
            } catch (\Exception $e) {
                Log::error('Errore salvataggio carta di credito: ' . $e->getMessage());
                Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
                DB::rollBack();
                
                return view('profilo.registrazione', [
                    'message' => 'Non puoi registrarti con questa carta',
                    'visitato' => ''
                ]);
            }
            
            // 2. Genera un codice univoco per la carta fedeltà
            $codiceFedelta = $this->generaCodiceFedelta();
            
            // 3. Inserisci la carta fedeltà
            $cartaFedelta = new CartaFedelta();
            $cartaFedelta->codice = $codiceFedelta;
            $cartaFedelta->punti = 0;
            
            try {
                $cartaFedelta->save();
            } catch (\Exception $e) {
                Log::error('Errore salvataggio carta fedeltà: ' . $e->getMessage());
                Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
                DB::rollBack();
                throw $e;
            }
            
            // 4. Crea il nuovo cliente

            $nuovoCliente = new Cliente();
            $nuovoCliente->nome = $nome;
            $nuovoCliente->cognome = $cognome;
            $nuovoCliente->email = $email;
            $nuovoCliente->cartadicredito = $codicecarta;
            $nuovoCliente->data_di_nascita = $data;
            $nuovoCliente->password = md5($password);
            $nuovoCliente->carta_fedelta = $codiceFedelta;
            
            try {
                $nuovoCliente->save();
            } catch (\Exception $e) {
                Log::error('Errore salvataggio cliente: ' . $e->getMessage());
                Log::error('Stack trace:', ['trace' => $e->getTraceAsString()]);
                DB::rollBack();
                throw $e;
            }
            
            DB::commit();
            
            // Redirect al login con messaggio di successo
            return redirect()->route('loginFirst')
                ->with('success', 'Registrazione completata con successo!');
            
        } catch (\Exception $e) {
            DB::rollBack();
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

    /**
     * Genera un codice univoco per la carta fedeltà
     */
    private function generaCodiceFedelta()
    {
        $tentativo = 0;
        
        do {
            $tentativo++;
            // Genera un numero casuale fino a 9 cifre
            $codice = (string) rand(100000000, 999999999);
            
            Log::info("Tentativo #$tentativo - Codice generato: $codice");
            
            // Verifica se esiste già
            $exists = CartaFedelta::where('codice', $codice)->exists();
            
            if ($exists) {
                Log::warning("Codice $codice già esistente, rigenero");
            }
            
        } while ($exists);
        
        Log::info("Codice fedeltà univoco trovato dopo $tentativo tentativi: $codice");
        return $codice;
    }

    public function logout(Request $request)
    {
        // Ottengo la sessione
        $log = $request->session()->get('log');
        // Se non esiste 'log' (utente non loggato)
        if ($log === null) {
            return redirect()->route('home');
        }
        
        // Altrimenti rimuovo gli attributi della sessione
        $request->session()->forget('log');
        $request->session()->forget('emailSession');

        // Redirect alla home
        return redirect()->route('login');
    }
}
