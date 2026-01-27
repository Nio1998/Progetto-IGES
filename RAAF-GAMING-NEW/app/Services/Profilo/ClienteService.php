<?php

namespace App\Services\Profilo;

use App\Models\Profilo\Cliente;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

class ClienteService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Cerca un cliente basandosi sulla chiave fornita.
     *
     * @param string $id.
     * @return \App\Models\Profilo\Cliente Il modello Cliente trovato.
     * @throws \InvalidArgumentException Se l'id fornito è vuoto o non valido.
     */
    public function ricercaPerChiave($id, $save = true)
    {
        if($id == null || $id == "")
            throw new \InvalidArgumentException("Inserito un id null o vuoto");
        

        $cliente = Session::get('Cliente');

        if(isset($cliente))
            if($cliente->email == $id)
                return $cliente;

        $cliente = Cliente::where('email',$id)->with(['cartacredito', 'cartafedelta'])->first() ?? null;

        if($save && $cliente != null)
            Session::put('Cliente',$cliente);

        return $cliente;
    }

    /**
     * Restituisce il cliente attualmente autenticato.
     *
     * Pre-condizione:
     *   - La sessione di Laravel è disponibile.
     *   - Se l'utente non è presente in sessione, deve essere possibile recuperare l'utente loggato.
     *
     * Post-condizione:
     *   - Restituisce un'istanza di \App\Models\Profilo\Cliente se l'utente è autenticato e presente nel database.
     *   - Se l'utente non è autenticato, restituisce null.
     *
     * @return \App\Models\Profilo\Cliente|null
     */
    public function getUtenteAutenticato()
    {
        return Session::get('Cliente');
    }

    /**
     * Rimuove il cliente attualmente memorizzato in sessione.
     *
     * Pre-condizione:
     *   - La sessione di Laravel è disponibile.
     *
     * Post-condizione:
     *   - Il cliente memorizzato in sessione sotto la chiave 'Cliente' viene rimosso.
     *   - Eventuali richiami a getUtenteAutenticato() dopo questa chiamata restituiranno null.
     *
     * @return void
     */
    public function logoutUtente()
    {
        Session::forget('Cliente');
    }

    /**
     * Restituisce la password criptata in MD5.
     *
     * @param string $password
     * @return string
     * @throws \InvalidArgumentException Se la password è null o vuota.
     */
    public function getCryptedPassword($password)
    {
        if ($password === null || $password === '')
            throw new \InvalidArgumentException("Password null o vuota");

        return md5($password);
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


    /**
     * Ritorna una Collection di Clienti (equivalente a ArrayList<ClienteBean>).
     * @param string $ordinamento La colonna su cui applicare l'ordinamento (es. 'cognome').
     * @return \Illuminate\Support\Collection|\App\Models\Cliente[] Una collezione di oggetti Cliente.
     * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
     */
    public function allElements($ordinamento)
    {
        if($ordinamento == null || $ordinamento == "")
            throw new \InvalidArgumentException("Inserito un ordinamento null o vuoto");

        switch($ordinamento)
        {
            case 'email asc':
                $cliente = Cliente::orderBy('email','asc')->get();
                break;
            case 'email desc':
                $cliente = Cliente::orderBy('email','desc')->get();
                break;
            case 'nome asc':
                $cliente = Cliente::orderBy('nome','asc')->get();
                break;
            case 'nome desc':
                $cliente = Cliente::orderBy('nome','desc')->get();
                break;
            case 'cognome asc':
                $cliente = Cliente::orderBy('cognome','asc')->get();
                break;
            case 'cognome desc':
                $cliente = Cliente::orderBy('cognome','desc')->get();
                break;
            case 'password asc':
                $cliente = Cliente::orderBy('password','asc')->get();
                break;
            case 'password desc':
                $cliente = Cliente::orderBy('password','desc')->get();
                break;
            case 'carta_fedelta asc':
                $cliente = Cliente::orderBy('carta_fedelta','asc')->get();
                break;
            case 'carta_fedelta desc':
                $cliente = Cliente::orderBy('carta_fedelta','desc')->get();
                break;
            case 'cartadicredito asc':
                $cliente = Cliente::orderBy('cartadicredito','asc')->get();
                break;
            case 'cartadicredito desc':
                $cliente = Cliente::orderBy('cartadicredito','desc')->get();
                break;
            default:  throw new \InvalidArgumentException("ordinamento scritto in modo errato");
        }

        return $cliente;
        
    }

    /**
     * Inserisce un nuovo cliente nel database (Equivalente a newInsert).
     * @param array $dati Un array associativo contenente i dati del cliente (es. dalla Request).
     * @return \App\Models\Cliente L'istanza del cliente appena creato.
     * @throws \InvalidArgumentException Se i dati forniti sono nulli o incompleti.
     */
    public function newInsert($item, $carta_fedelta, $cartadicredito)
    {
        if($item == null || $carta_fedelta == null || $cartadicredito == null)
            throw new \InvalidArgumentException("Inserito un item o carta_fedelta o cartadicredito null");

        if ($item->exists)
            throw new \InvalidArgumentException("Inserito un item già esistente");

        $item->save();
        
        $item->setRelation('cartafedelta', $carta_fedelta);
        $item->setRelation('cartacredito', $cartadicredito);
    }

    /**
	 * @param item L'oggetto {@link ClienteBean} contenente i nuovi dati del cliente. 
	 * Non deve essere {@code null}
	 * @throws \InvalidArgumentException Se l'oggetto {@code item} passato come parametro è {@code null}.
	 */
    public function doUpdate($item){
        if($item == null)
			throw new \InvalidArgumentException("Inserito un item null");

        $item->update();
        Session::put('Cliente', $item);
    }
}
