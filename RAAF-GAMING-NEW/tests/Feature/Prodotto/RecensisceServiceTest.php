<?php

use App\Services\Prodotto\RecensisceService;
use Database\Seeders\TestRecensisceSeeder;
use Database\Seeders\TestProdottoSeeder;
use Database\Seeders\TestClienteSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses()->group('RecensisceUnit', 'Unit');

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
        $schemaCliente    = base_path('tests/resources/init/cliente.sql');
        $schemaProdotto   = base_path('tests/resources/init/prodotto.sql');
        $schemaRecensisce = base_path('tests/resources/init/recensisce.sql');
        
       if (file_exists($schemaRecensisce)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaCliente));
            DB::unprepared(file_get_contents($schemaProdotto));
            DB::unprepared(file_get_contents($schemaRecensisce));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaRecensisce}");
        }
        $this->seed(TestClienteSeeder::class);
        $this->seed(TestProdottoSeeder::class);
        $this->seed(TestRecensisceSeeder::class);
 
        $dbInitialized = true;
    }
    
    // Inizia transazione per ogni test
    DB::beginTransaction();
    
});

afterEach(function () {
    DB::rollback();
});

test('pubblicaRecensioneREPBCBVVCOV', function () {

    $recensisceService = new RecensisceService();

    $cliente = 'antoniomaddaloni@hotmail.com';
    $prodottoId = 2;
    $voto = 7;
    $commento = 'vero veramente';

    $result = $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento);

    // Viene restituito false, perchè una recensione per quel prodotto
    // fatta da quel cliente già esiste
    expect($result)->toBeFalse();

});

test('pubblicaRecensioneRNEPBCBVVCOV', function () {

    $recensisceService = new RecensisceService();

    $cliente    = 'f.peluso25@gmail.com';
    $prodottoId = 1;
    $voto       = 10;
    $commento   = 'bello';

    $result = $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento);

    // true perché non esiste ancora una recensione di f.peluso25@gmail.com per il prodotto 1
    expect($result)->toBeTrue();

    // Verifica che la recensione sia stata effettivamente inserita nel DB
    $actual = DB::table('recensisce')->get()->toArray();
    $expected = require base_path('tests/resources/expected/RecensiscePublicaNuovaRecensione.php');

    expect($actual)->toHaveCount(count($expected));

    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }
}); 


test('pubblicaRecensioneRNEPNBCBVVCOV', function () {

    $recensisceService = new RecensisceService();

    $cliente    = 'f.peluso25@gmail.com';
    $prodottoId = 100;
    $voto       = 10;
    $commento   = 'bello';

    // La FK è attiva, prodotto 100 non esiste → QueryException
    expect(fn() => $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

test('pubblicaRecensioneRNEPNCBVVCOV', function () {

    $recensisceService = new RecensisceService();

    $cliente    = 'f.peluso25@gmail.com';
    $prodottoId = null;
    $voto       = 10;
    $commento   = 'bello';

    // Viene generato un errore perchè il codice del prodotto è null
    expect(fn() => $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento))
        ->toThrow(\TypeError::class);
});

test('pubblicaRecensioneRNEPBCNBVVCOV', function () {

    $recensisceService = new RecensisceService();

    $cliente    = 'prova@gmail.com';
    $prodottoId = 1;
    $voto       = 10;
    $commento   = 'bello';

    expect(fn() => $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento))
        ->toThrow(\Illuminate\Database\QueryException::class);
});

test ('pubblicaRcensioneRNEPBCNVVCOV',function(){

    $recensisceService = new RecensisceService();

    $cliente    = null;
    $prodottoId = 1;
    $voto       = 10;
    $commento   = 'bello';

    // Viene generato un errore perchè il codice del prodotto è null
    expect(fn() => $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento))
        ->toThrow(\TypeError::class);

});

test ('pubblicaRcensioneRNEPBCBVNCOV',function(){

    $recensisceService = new RecensisceService();

    $cliente    = 'f.peluso25@gmail.com';
    $prodottoId = 1;
    $voto       = null;
    $commento   = 'bello';

    // Viene generato un errore perchè il codice del prodotto è null
    expect(fn() => $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento))
        ->toThrow(\TypeError::class);

});

test ('pubblicaRcensioneRNEPBCBVVCON',function(){

    $recensisceService = new RecensisceService();

    $cliente    = 'f.peluso25@gmail.com';
    $prodottoId = 1;
    $voto       = 10;
    $commento   = null;

    // Viene generato un errore perchè il codice del prodotto è null
    expect(fn() => $recensisceService->pubblicaRecensione($cliente, $prodottoId, $voto, $commento))
        ->toThrow(\TypeError::class);

});

test('testAllElementsOASC', function () {
    $recensisceService = new RecensisceService();
    $ordinamento = "cliente asc";
    $output = $recensisceService->allElements($ordinamento);
    $output->toArray();
    $expected = require base_path('tests/resources/expected/RecensiscePublicaRecensioneAsc.php');
    expect($output)->toHaveCount(count($expected));
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $output[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testAllElementsODESC', function () {
    $recensisceService = new RecensisceService();
    $ordinamento = "cliente desc";
    $output = $recensisceService->allElements($ordinamento);
    $output->toArray();
    $expected = require base_path('tests/resources/expected/RecensiscePublicaRecensioneDesc.php');
    expect($output)->toHaveCount(count($expected));
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $output[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testAllElementsONONVAL', function () {
    $recensisceService = new RecensisceService();

    expect(fn() => $recensisceService->allElements('prova'))
        ->toThrow(\InvalidArgumentException::class, "ordinamento non valido");
});

test('testAllElementsOV', function () {
    $recensisceService = new RecensisceService();

    expect(fn() => $recensisceService->allElements(''))
        ->toThrow(\InvalidArgumentException::class, "ordinamento vuoto o null");
});

test('testAllElementsON', function () {
    $recensisceService = new RecensisceService();

    expect(fn() => $recensisceService->allElements(null))
        ->toThrow(\InvalidArgumentException::class, "ordinamento vuoto o null");
});