<?php

namespace App\Services\Profilo;

use App\Models\Profilo\CartaDiCredito;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CartaDiCreditoService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Cerca una carta di credito basandosi sulla chiave fornita.
     *
     * @param string $id Il codice della carta di credito.
     * @return \App\Models\Profilo\CartaDiCredito|null Il modello CartaDiCredito trovato o null.
     * @throws \InvalidArgumentException Se l'id fornito è vuoto o non valido.
     */
    public function ricercaPerChiave(?string $id): ?CartaDiCredito
    {
        if($id == null || $id == "")
            throw new \InvalidArgumentException("Inserito un id null o vuoto");

        return CartaDiCredito::where('codicecarta', $id)->with('cliente')->first();
    }

    /**
     * Inserisce una nuova carta di credito nel database.
     *
     * @param \App\Models\Profilo\CartaDiCredito $item L'oggetto CartaDiCredito da inserire.
     * @return void
     * @throws \InvalidArgumentException Se l'item fornito è null.
     */
    public function newInsert(CartaDiCredito $item): void
    {
        if($item == null)
            throw new \InvalidArgumentException("Inserito un item null");
        
        $item->save();
    }

    /**
     * Aggiorna una carta di credito esistente.
     *
     * @param \App\Models\Profilo\CartaDiCredito $item L'oggetto CartaDiCredito contenente i nuovi dati.
     * @param string $codice Il codice della carta da aggiornare.
     * @return void
     * @throws \InvalidArgumentException Se l'item o il codice sono null.
     */
    public function doUpdate(?CartaDiCredito $item, ?string $codice): void
    {
        if($item == null || $codice == null)
            throw new \InvalidArgumentException("Inserito un item null o codice null");

        $cartaEsistente = CartaDiCredito::where('codicecarta', $codice)->with('cliente')->first();

        if($cartaEsistente) {
            $emailclientecollegato = $cartaEsistente->cliente->email;
            $cartaEsistente->codicecarta = $item->codicecarta;
            $cartaEsistente->data_scadenza = $item->data_scadenza;
            $cartaEsistente->codice_cvv = $item->codice_cvv;
            $cartaEsistente->update();

            $cliente = Session::get('Cliente');

            if(isset($cliente))
            {
                if($cliente->cartacredito && ($cliente->email == $emailclientecollegato))
                {
                    $cliente->setRelation('cartacredito', $cartaEsistente);
                    $cliente->cartadicredito = $cartaEsistente->codicecarta;            
                    Session::put('Cliente', $cliente);
                }
            }           
        }
    }
}