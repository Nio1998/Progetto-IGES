<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Profilo\Autenticazione;

Route::get('/', function () {
    return view('layouts.appoggio');
});

Route::get('/LoginFirst', [Autenticazione::class,'LoginFirt'])->name('Login');
Route::post('/LoginFirst', [Autenticazione::class,'LoginFirt'])->name('Login');

