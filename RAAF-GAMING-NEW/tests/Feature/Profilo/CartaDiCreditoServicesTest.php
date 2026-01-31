<?php

use App\Models\Profilo\CartaDiCredito;
use Database\Seeders\TestCartaDiCreditoSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\Profilo\CartaDiCreditoService;
use Illuminate\Support\Facades\Session;

uses()->group('CartaDiCreditoUnit', 'Unit');

$dbInitialized = false;

// Cleanup del DB
afterAll(function () {
    // Elimina tutte le tabelle
    Schema::dropAllTables();
});

// Inizializzazione Del DB e dei Dati (solo quelli che servono) e mantiene i dati puliti tra i test con transazioni
beforeEach(function () use (&$dbInitialized) {

    if (!$dbInitialized) {
        // Path delle singole tabelle che servono
        $schemaCarta = base_path('tests/resources/init/cartadicredito.sql');
        $schemaCliente = base_path('tests/resources/init/cliente.sql'); 
        
        if (file_exists($schemaCarta)) {
            DB::unprepared(file_get_contents($schemaCarta));
        }
        
        // AGGIUNGI la tabella cliente
        if (file_exists($schemaCliente)) {
            DB::unprepared(file_get_contents($schemaCliente));
        }
        
        // Esegui i seeder
        $this->seed(TestCartaDiCreditoSeeder::class);
        // Probabilmente serve anche un seeder per i clienti
        
        $dbInitialized = true;
    }
    
    DB::beginTransaction();
    Session::flush();
});

afterEach(function () {
    DB::rollback();
    Session::flush();
});

test('testRicercaPerChiavePresenteDB', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    $output = $cartaDiCreditoService->ricercaPerChiave('1234123412341235');

    // Verifica che il risultato non sia null
    expect($output)->not->toBeNull()
        // Verifica che sia un'istanza di CartaDiCredito
        ->and($output)->toBeInstanceOf(CartaDiCredito::class)
        // Verifica che il codice corrisponda
        ->and($output->codicecarta)->toBe('1234123412341235');
});

test('testRicercaPerChiaveNonPresenteDB', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    $output = $cartaDiCreditoService->ricercaPerChiave('0');

    // Verifica che il risultato non sia null
    expect($output)->not->toBeNull();

});


