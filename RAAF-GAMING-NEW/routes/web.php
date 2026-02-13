<?php

use App\Http\Controllers\Prodotto\GestioneOrdiniAdmin;
use App\Http\Controllers\Prodotto\GestioneProdottiAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profilo\Autenticazione;
use App\Http\Controllers\Profilo\AutenticazioneAdmin;
use App\Http\Controllers\Profilo\Profilo;
use App\Http\Controllers\Prodotto\Prodotto;

// Home 
Route::get('/', [Prodotto::class, 'index'])->name('home');
// LOGOUT
Route::match(['GET', 'POST'], '/logout', [Autenticazione::class, 'logout'])->name('logout');
// LOGOUTADMIN
Route::match(['GET', 'POST'], '/logoutAdmin', [AutenticazioneAdmin::class, 'logoutAdmin'])->name('logoutAdmin');

Route::get('/ricerca', [Prodotto::class, 'ricercaProdotto'])->name('prodotto.ricerca');
Route::get('/prodotto/dettaglio', [Prodotto::class, 'show'])->name('prodotto.show');
Route::get('/prodotto/copertina/{codice}', [Prodotto::class, 'getImmagine'])->name('prodotto.getImmagine');
Route::get('/aggiungiCarrello',[Prodotto::class, 'aggiungiCarrelo'])->name('prodotto.aggiungiCarrello');

// Tutte le rotte “guest” passano per il middleware
Route::middleware(['redirectIfAuthenticated'])->group(function () {
    // LOGIN
    Route::get('/login', [Autenticazione::class, 'loginFirst'])->name('loginFirst');
    Route::post('/login', [Autenticazione::class, 'login'])->name('login');

    // REGISTRAZIONE
    Route::get('/registrazione', [Autenticazione::class, 'registrazione'])->name('registrazione');
    Route::post('/registrazione', [Autenticazione::class, 'registrazioneStore'])->name('registrazione.store');
});

Route::middleware(['redirectIfAuthenticatedAdmin'])->group(function () { 
    // LOGIN ADMIN
    Route::get('/admin', [AutenticazioneAdmin::class, 'loginFirstAdmin'])->name('loginFirstAdmin');
    Route::post('/admin', [AutenticazioneAdmin::class, 'loginAdmin'])->name('loginAdmin');
});

// Tutte le rotte quando l'utente è autenticato
Route::middleware(['isAutenticated'])->group(function () {

    // PROFILO
    Route::get('/profilo', [Profilo::class, 'mostraProfilo'])->name('mostraProfilo');
    Route::post('/profilo', [Profilo::class, 'modificaProfilo'])->name('modificaProfilo');
    Route::post('/recensione',[Prodotto::class,'aggiungiRecensione'])->name('recensione.store');
    

});

// Tutte le rotte quando l'utente admin è autenticato
Route::middleware(['isAutenticatedAdmin'])->group(function () {

    //PER GESTORE ORDINI
    Route::middleware(['GestoreOrdini'])->group(function () {

        Route::get('/homeOrdine', [GestioneOrdiniAdmin::class, 'homeOrdine'])->name('homeOrdine');
    });

    //PER GESTORE PRODOTTO
    Route::middleware(['GestoreProdotti'])->group(function () {

        Route::get('/homeProdotto', [GestioneProdottiAdmin::class, 'homeProdotto'])->name('homeProdotto');
        Route::post('/formProdNuovo', [GestioneProdottiAdmin::class, 'formProdNuovoAdmin'])->name('formProdNuovo');
        Route::post('/formProdEsistenti', [GestioneProdottiAdmin::class, 'formProdEsistentiAdmin'])->name('formProdEsistenti');
    });
});