<?php

namespace App\Http\Controllers\Profilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Profilo extends Controller
{
    public function mostraProfilo()
    {
        return view('PresentazioneProfilo.profilo');
    }
}
