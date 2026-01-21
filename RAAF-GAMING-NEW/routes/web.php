<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profilo\Autenticazione;
use App\Http\Controllers\Profilo\AutenticazioneAdmin;
use App\Http\Controllers\Profilo\Profilo;

// Home temporanea
Route::get('/', function () {
    return view('layouts.appoggio');
})->name('home');
// Home temporanea

// LOGOUT
Route::match(['GET', 'POST'], '/logout', [Autenticazione::class, 'logout'])->name('logout');

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

});