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
    public function ricercaPerChiave($id)
    {
        if($id == null || $id == "")
			throw new \InvalidArgumentException("Inserito un id null o vuoto");

        $cliente = Session::get('Cliente');

        if(isset($cliente))
            return $cliente;

        $cliente = Cliente::where('email',$id)->first();

        Session::put('Cliente',$cliente);

        return $cliente;
    }

    /**
     * Ritorna una Collection di Clienti (equivalente a ArrayList<ClienteBean>).
     * * @param string $ordinamento La colonna su cui applicare l'ordinamento (es. 'cognome').
     * @return \Illuminate\Support\Collection|\App\Models\Cliente[] Una collezione di oggetti Cliente.
     * * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
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
     *
     * @param array $dati Un array associativo contenente i dati del cliente (es. dalla Request).
     * @return \App\Models\Cliente L'istanza del cliente appena creato.
     * * @throws \InvalidArgumentException Se i dati forniti sono nulli o incompleti.
     */
    public function newInsert($item, $carta_fedelta, $cartadicredito)
    {
        if($item == null || $carta_fedelta == null || $cartadicredito == null)
            throw new \InvalidArgumentException("Inserito un item o carta_fedelta o cartadicredito null");

        $item->save();
        
        $item->setRelation('cartafedelta', $carta_fedelta);
        $item->setRelation('cartacredito', $cartadicredito);
    }



    
}
