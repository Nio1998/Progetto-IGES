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
     * Ritorna una Collection di Gestori (equivalente a ArrayList<GestoreBean>).
     * @param string $ordinamento La colonna su cui applicare l'ordinamento (es. 'email asc').
     * @return \Illuminate\Support\Collection|\App\Models\Profilo\Gestore[] Una collezione di oggetti Gestore.
     * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
     */
    public function allElements($ordinamento)
    {
        if($ordinamento == null || $ordinamento == "")
            throw new \InvalidArgumentException("Inserito un ordinamento null o vuoto");

        switch($ordinamento)
        {
            case 'email asc':
                $gestore = Gestore::orderBy('email', 'asc')->get();
                break;
            case 'email desc':
                $gestore = Gestore::orderBy('email', 'desc')->get();
                break;
            case 'ruolo asc':
                $gestore = Gestore::orderBy('ruolo', 'asc')->get();
                break;
            case 'ruolo desc':
                $gestore = Gestore::orderBy('ruolo', 'desc')->get();
                break;
            case 'password asc':
                $gestore = Gestore::orderBy('password', 'asc')->get();
                break;
            case 'password desc':
                $gestore = Gestore::orderBy('password', 'desc')->get();
                break;
            default:
                throw new \InvalidArgumentException("ordinamento scritto in modo errato");
        }

        return $gestore;
    }

    /**
     * Inserisce un nuovo gestore nel database.
     * @param \App\Models\Profilo\Gestore $item L'istanza del gestore da salvare.
     * @return \App\Models\Profilo\Gestore L'istanza del gestore appena creato.
     * @throws \InvalidArgumentException Se i dati forniti sono nulli.
     */
    public function newInsert($item)
    {
        if($item == null)
            throw new \InvalidArgumentException("Inserito un item null");

        $item->save();
        
        return $item;
    }

    /**
     * Aggiorna un gestore esistente nel database.
     * @param \App\Models\Profilo\Gestore $item L'oggetto Gestore contenente i nuovi dati.
     * Non deve essere null.
     * @throws \InvalidArgumentException Se l'oggetto item passato come parametro è null.
     */
    public function doUpdate($item)
    {
        if($item == null)
            throw new \InvalidArgumentException("Inserito un item null");
        
        $item->update();
        Session::put('Gestore', $item);
    }

    /**
     * Elimina un gestore dal database.
     * @param string $id L'email del gestore da eliminare.
     * @return bool True se l'eliminazione è avvenuta con successo.
     * @throws \InvalidArgumentException Se l'id fornito è vuoto o non valido.
     */
    public function doDelete($id)
    {
        if($id == null || $id == "")
            throw new \InvalidArgumentException("Inserito un id null o vuoto");

        $gestore = Gestore::where('email', $id)->first();
        
        if($gestore != null) {
            $gestore->delete();
            return true;
        }
        
        return false;
    }
}