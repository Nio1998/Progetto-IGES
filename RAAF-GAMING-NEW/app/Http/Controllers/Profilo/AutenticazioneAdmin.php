<?php

namespace App\Http\Controllers\Profilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Profilo\GestoreService;

class AutenticazioneAdmin extends Controller
{
    public function loginFirstAdmin(){
        return view('PresentazioneProfilo.admin');
    }

    public function loginAdmin(Request $request)
    {
        //dd($request->all()); // Debug: mostra tutti i dati della richiesta
        $gestoreService = new GestoreService();
        $email = $request->input('email');
        $password = $request->input('password');

        // Se email o password mancanti → ritorna al form
        if (!$email || !$password) {
            return view('PresentazioneProfilo.admin', [
                'message' => 'Inserisci email e password',
                'visita' => ''
            ]);
        }

        // Trova admin
       $admin = $gestoreService->ricercaPerChiave($email);
        //dd($utente);
        if (!$admin) {
            return view('PresentazioneProfilo.admin', [
                'message' => 'Email non registrata',
                'visita' => ''
            ]);
        }

        // Controllo password (MD5 per compatibilità)
        if (md5($password) !== $admin->password) {
            return view('PresentazioneProfilo.admin', [
                'message' => 'Password errata',
                'visita' => ''
            ]);
        }

        //dd(Session::get('Cliente');
        $ruolo = $admin->ruolo;
        if($ruolo == 'ordine'){
            return redirect()->route('homeOrdine'); 
        }
        else
            return redirect()->route('homeProdotto'); 
    }

    public function logoutAdmin()
    {
        // Rimuovi il gestore dalla sessione
        $gestoreService = new GestoreService();
        $gestoreService->logoutUtente();

        // Reindirizza alla pagina di login
        return redirect()->route('loginAdmin');
    }
}
