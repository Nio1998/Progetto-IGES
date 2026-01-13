<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profilo\Autenticazione;

// Home temporanea
Route::get('/', function () {
    return view('layouts.appoggio');
})->name('home');

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

