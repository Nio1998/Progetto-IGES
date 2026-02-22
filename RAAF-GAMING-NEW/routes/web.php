<?php

use App\Http\Controllers\Acquisto\Carrello;
use App\Http\Controllers\Acquisto\GestioneOrdiniAdmin;
use App\Http\Controllers\Acquisto\Ordini;
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
Route::post('/aggiungiCarrello',[Prodotto::class, 'aggiungiCarrello'])->name('prodotto.aggiungiCarrello');
Route::get('/prodotto/categoria/{categoria}', [Prodotto::class, 'ricercaPerCategoria'])->name('prodotto.ricercaCategoria');
Route::get('/carrello', [Carrello::class, 'carrello'])->name('carrello.show');
Route::post('/carrello-delete', [Carrello::class, 'eliminaCarrello'])->name('carrello.delete');

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

    Route::post('/carrello-conferma-acquisto', [Carrello::class, 'confermaAcquisto'])->name('carrello.shop');
    
        
    Route::get('/ordini', [Ordini::class, 'index'])->name('ordini.index');

});

// Tutte le rotte quando l'utente admin è autenticato
Route::middleware(['isAutenticatedAdmin'])->group(function () {

    //PER GESTORE ORDINI
    Route::middleware(['GestoreOrdini'])->group(function () {

        Route::get('/homeOrdine', [GestioneOrdiniAdmin::class, 'homeOrdine'])->name('homeOrdine');
        Route::post('/formOrdini', [GestioneOrdiniAdmin::class, 'formOrdiniAdmin'])->name('formOrdini');
    });

    //PER GESTORE PRODOTTO
    Route::middleware(['GestoreProdotti'])->group(function () {

        Route::get('/homeProdotto', [GestioneProdottiAdmin::class, 'homeProdotto'])->name('homeProdotto');
        Route::post('/formProdNuovo', [GestioneProdottiAdmin::class, 'formProdNuovoAdmin'])->name('formProdNuovo');
        Route::post('/formProdEsistenti', [GestioneProdottiAdmin::class, 'formProdEsistentiAdmin'])->name('formProdEsistenti');
    });
});