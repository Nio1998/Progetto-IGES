<?php

namespace App\Services\Profilo;

use App\Models\Profilo\Gestore;
use Illuminate\Support\Facades\Session;

class GestoreService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Cerca un gestore basandosi sulla chiave fornita.
     *
     * @param string $id L'email del gestore da cercare.
     * @param bool $save Se true, salva il gestore in sessione.
     * @return \App\Models\Profilo\Gestore|null Il modello Gestore trovato o null.
     * @throws \InvalidArgumentException Se l'id fornito è vuoto o non valido.
     */
    public function ricercaPerChiave($id, $save = true)
    {
        if($id == null || $id == "")
            throw new \InvalidArgumentException("Inserito un id null o vuoto");
        
        $gestore = Session::get('Gestore');

        if(isset($gestore))
            if($gestore->email == $id)
                return $gestore;

        $gestore = Gestore::where('email', $id)->first() ?? null;

        if($save && $gestore != null)
            Session::put('Gestore', $gestore);

        return $gestore;
    }

    /**
     * Restituisce il gestore attualmente autenticato.
     *
     * Pre-condizione:
     *   - La sessione di Laravel è disponibile.
     *
     * Post-condizione:
     *   - Restituisce un'istanza di \App\Models\Profilo\Gestore se l'utente è autenticato.
     *   - Se l'utente non è autenticato, restituisce null.
     *
     * @return \App\Models\Profilo\Gestore|null
     */
    public function getUtenteAutenticato()
    {
        return Session::get('Gestore');
    }

    /**
     * Rimuove il gestore attualmente memorizzato in sessione.
     *
     * Pre-condizione:
     *   - La sessione di Laravel è disponibile.
     *
     * Post-condizione:
     *   - Il gestore memorizzato in sessione sotto la chiave 'Gestore' viene rimosso.
     *   - Eventuali richiami a getUtenteAutenticato() dopo questa chiamata restituiranno null.
     *
     * @return void
     */
    public function logoutUtente()
    {
        Session::forget('Gestore');
    }

    /**
     * Verifica se la password fornita corrisponde a quella dell'utente.
     *
     * @param string $password
     * @param \App\Models\Profilo\Cliente $utente
     * @return bool
     * @throws \InvalidArgumentException Se i parametri sono null.
     */
    public function checkPassword($password, $utente)
    {
        if ($password === null || $utente === null)
            throw new \InvalidArgumentException("Password o utente null");

        return md5($password) === $utente->password;
    }

}