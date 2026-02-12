<?php

namespace App\Http\Controllers\Prodotto;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Prodotto\CategoriaService;

class Categorie extends Controller
{
    public function getCategoria($nome)
    {
        $categoriaService = new CategoriaService();
        $prodotti = $categoriaService->allElements($nome, 'asc');

        return view('prodotto.paginaRicerca',compact('prodotti'));
        
    }
}
