<?php

namespace App\Services\Profilo;

use App\Models\Profilo\CartaFedelta;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartaFedeltaService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Cerca una carta fedeltà basandosi sulla chiave fornita.
     *
     * @param string $id Il codice della carta fedeltà.
     * @return \App\Models\Profilo\CartaFedelta|null Il modello CartaFedelta trovato o null.
     * @throws \InvalidArgumentException Se l'id fornito è vuoto o non valido.
     */
    public function ricercaPerChiave($id)
    {
        if($id == null || $id == "")
            throw new \InvalidArgumentException("Inserito un id null o vuoto");

        return CartaFedelta::where('codice', $id)->with('utente')->first();
    }

    /**
     * Ritorna una Collection di CarteFedelta ordinate.
     *
     * @param string $ordinamento La colonna e direzione per l'ordinamento (es. 'codice asc').
     * @return \Illuminate\Support\Collection|\App\Models\Profilo\CartaFedelta[] Una collezione di oggetti CartaFedelta.
     * @throws \InvalidArgumentException Se il parametro di ordinamento non è valido.
     */
    public function allElements($ordinamento)
    {
        if($ordinamento == null || $ordinamento == "")
            throw new \InvalidArgumentException("Inserito un ordinamento null o vuoto");

        switch($ordinamento)
        {
            case 'codice asc':
                $carte = CartaFedelta::orderBy('codice','asc')->get();
                break;
            case 'codice desc':
                $carte = CartaFedelta::orderBy('codice','desc')->get();
                break;
            case 'punti asc':
                $carte = CartaFedelta::orderBy('punti','asc')->get();
                break;
            case 'punti desc':
                $carte = CartaFedelta::orderBy('punti','desc')->get();
                break;
            default:
                throw new \InvalidArgumentException("ordinamento scritto in modo errato");
        }

        return $carte;
    }

    /**
     * Inserisce una nuova carta fedeltà nel database.
     *
     * @param \App\Models\Profilo\CartaFedelta $item L'oggetto CartaFedelta da inserire.
     * @return void
     * @throws \InvalidArgumentException Se l'item fornito è null.
     */
    public function newInsert($item)
    {
        if($item == null)
            throw new \InvalidArgumentException("Inserito un item null");

        $item->save();
    }

    /**
     * Aggiorna i punti di una carta fedeltà incrementandoli di 1.
     *
     * @param \App\Models\Profilo\CartaFedelta $item L'oggetto CartaFedelta da aggiornare.
     * @return void
     * @throws \InvalidArgumentException Se il codice dell'item è null o vuoto.
     */
    public function doUpdate($item)
    {
        if($item == null || $item->codice == null)
            throw new \InvalidArgumentException("Inserito un id null o vuoto");

        $carta = CartaFedelta::where('codice', $item->codice)->with('utente')->first();
        
        if($carta) {
            $carta->punti = $carta->punti + 1;
            $carta->update();

            $cliente = Session::get('Cliente');

            if(isset($cliente))
            {
                if($cliente->cartafedelta && ($cliente->email == $carta->utente->email))
                {
                    $cliente->cartafedelta = $carta;
                    Session::put('Cliente', $cliente);
                }
            }           
        }
    }

    /**
    * Genera un codice univoco per la carta fedeltà
    */
    public function generaCodiceFedelta()
    {
        $tentativo = 0;        
        do {
            $tentativo++;
            // Genera un numero casuale fino a 9 cifre
            $codice = (string) rand(100000000, 999999999);
            
            
            // Verifica se esiste già
            $carta = CartaFedelta::where('codice', $codice)->value('codice');
            $exists = $carta !== null;
            
        } while ($exists);
        
        return $codice;
    }
}