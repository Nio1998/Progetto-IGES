<?php

namespace App\Services\Profilo;

use App\Models\Profilo\Cliente;
use Illuminate\Support\Facades\Session;

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

        $cliente = Cliente::where('email',$id);

        Session::put('Cliente',$cliente);

        return $cliente;
    }

    
}
