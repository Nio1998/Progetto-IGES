<?php

use App\Models\Profilo\CartaDiCredito;
use App\Models\Profilo\CartaFedelta;
use App\Models\Profilo\Cliente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\Profilo\CartaDiCreditoService;
use App\Services\Profilo\CartaFedeltaService;
use Database\Seeders\TestCartaFedeltaSeeder;
use Illuminate\Support\Facades\Session;

uses()->group('CartaDiFedeltaUnit', 'Unit');

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
        $schemaCarta = base_path('tests/resources/init/cartafedelta.sql');
        $schemaCliente = base_path('tests/resources/init/cliente.sql'); 
        
        if (file_exists($schemaCarta)) {
            DB::unprepared(file_get_contents($schemaCarta));
        }
        
        // AGGIUNGI la tabella cliente
        if (file_exists($schemaCliente)) {
            DB::unprepared(file_get_contents($schemaCliente));
        }
        
        // Esegui i seeder
        $this->seed(TestCartaFedeltaSeeder::class);
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

test('testRicercaPerChiavePresente', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    $output = $cartaFedeltaService->ricercaPerChiave('1234567897');

    // Verifica che il risultato non sia null
    expect($output)->not->toBeNull()
        // Verifica che sia un'istanza di CartaFedeltà
        ->and($output)->toBeInstanceOf(CartaFedelta::class)
        // Verifica che il codice corrisponda
        ->and($output->codice)->toBe('1234567897')
        ->and($output->punti)->toBe(0);
});

test('testRicercaPerChiaveNonPresente', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    $output = $cartaFedeltaService->ricercaPerChiave('1234567896');

    // Verifica che il risultato non sia null
    expect($output)->toBeNull();
        // Verifica che sia un'istanza di CartaFedeltà
});

test('testRicercaPerChiaveVuoto', function () {
    $cartaFedeltaService = new CartaFedeltaService();

        expect(fn() => $cartaFedeltaService->ricercaPerChiave(""))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testRicercaPerChiaveNull', function () {
    $cartaFedeltaService = new CartaFedeltaService();

        expect(fn() => $cartaFedeltaService->ricercaPerChiave(null))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testAllElementsCrescente', function () {
    $cartaFedeltaService = new CartaFedeltaService();

        expect(fn() => $cartaFedeltaService->ricercaPerChiave(null))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});