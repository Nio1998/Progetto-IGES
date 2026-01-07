<?php

namespace App\Http\Controllers\Profilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Autenticazione extends Controller
{
    public function LoginFirt(Request $request){
        return view('PresentazioneProfilo.login');
    }

    
}
