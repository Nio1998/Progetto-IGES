<?php

use App\Models\Prodotto\Fornitore;
use App\Services\Prodotto\FornitoreService;
use Database\Seeders\TestFornitoreSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Collection\Collection;

uses()->group('FornitoreUnit', 'Unit');

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
        $schemaFornitore = base_path('tests/resources/init/fornitore.sql');
        
        if (file_exists($schemaFornitore)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaFornitore));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaFornitore}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestFornitoreSeeder::class);
        
        $dbInitialized = true;
    }
    
    // Inizia transazione per ogni test
    DB::beginTransaction();
    Session::flush();
    Cache::flush();
});

afterEach(function () {
    DB::rollback();
    Session::flush();
    Cache::flush();
});
/* PER ESSERE ATTIVATO DOBBIMO CAMBIARE IL METODO LOADMAGAZZINI DA PRIVATE A PUBLIC
test('testLoadFornitoriDCDID', function () {
    $FornitoriCache = collect([
        new Fornitore(['nome' => 'AMD','indirizzo' => 'Giappone', 'telefono' => '089343743']),
    ])->keyBy('nome');

    Cache::put('Fornitori', $FornitoriCache, 60);

    $categorie = new FornitoreService();
    $output = $categorie->loadFornitori();

    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->first()->nome)->toBe('AMD');
    expect($output->first()->indirizzo)->toBe('Giappone');
    expect($output->first()->telefono)->toBe('089343743');
});


test('testLoadFornitoriDNCDID', function () {
    $fornitori = new FornitoreService();
    $output = $fornitori->loadFornitori();
    
    // Test/Asserzioni
    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->values()[0]->nome)->toBe('AMD');
    expect($output->values()[0]->indirizzo)->toBe('Giappone');
    expect($output->values()[0]->telefono)->toBe('089343743');
    expect($output->values()[1]->nome)->toBe('Nvidia');
    expect($output->values()[1]->indirizzo)->toBe('America');
    expect($output->values()[1]->telefono)->toBe('089343701');
    expect($output->values()[2]->nome)->toBe('Sony');
    expect($output->values()[2]->indirizzo)->toBe('Giappone');
    expect($output->values()[2]->telefono)->toBe('081931798');


});

test('testLoadCategorieDNCDNID', function () {
    DB::table('fornitore')->delete();
    $fornitori = new FornitoreService();
    $output = $fornitori->loadFornitori();
    
    // Test/Asserzioni
    expect($output)->toBeEmpty();
});
*/
test('testAllElementsOASC', function () {

    $FornitoriCache = collect([
        new Fornitore(['nome' => 'AMD','indirizzo' => 'Giappone', 'telefono' => '089343743']),
        new Fornitore(['nome' => 'Nvidia','indirizzo' => 'America', 'telefono' => '089343701']),
        new Fornitore(['nome' => 'Sony','indirizzo' => 'Giappone', 'telefono' => '081931798']),
    ])->keyBy('nome');

    Cache::put('Fornitori', $FornitoriCache, 60);;

    $fornitoriService = new FornitoreService();
    $ordinamento = "nome asc";
    $output = $fornitoriService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/FornitoriAsc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testAllElementsODESC', function () {

    $FornitoriCache = collect([
        new Fornitore(['nome' => 'AMD','indirizzo' => 'Giappone', 'telefono' => '089343743']),
        new Fornitore(['nome' => 'Nvidia','indirizzo' => 'America', 'telefono' => '089343701']),
        new Fornitore(['nome' => 'Sony','indirizzo' => 'Giappone', 'telefono' => '081931798']),
    ])->keyBy('nome');

    Cache::put('Fornitori', $FornitoriCache, 60);;

    $fornitoriService = new FornitoreService();
    $ordinamento = "nome desc";
    $output = $fornitoriService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/FornitoriDesc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});


test('testAllElementsONONVAL', function () {

    $FornitoriCache = collect([
        new Fornitore(['nome' => 'AMD','indirizzo' => 'Giappone', 'telefono' => '089343743']),
        new Fornitore(['nome' => 'Nvidia','indirizzo' => 'America', 'telefono' => '089343701']),
        new Fornitore(['nome' => 'Sony','indirizzo' => 'Giappone', 'telefono' => '081931798']),
    ])->keyBy('nome');

    Cache::put('Fornitori', $FornitoriCache, 60);;

    $fornitoriService = new FornitoreService();
    $ordinamento = "Napoli ssc";

    expect(fn() => $fornitoriService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Ordinamento scritto in modo errato");
});

test('testAllElementsOV', function () {

    $FornitoriCache = collect([
        new Fornitore(['nome' => 'AMD','indirizzo' => 'Giappone', 'telefono' => '089343743']),
        new Fornitore(['nome' => 'Nvidia','indirizzo' => 'America', 'telefono' => '089343701']),
        new Fornitore(['nome' => 'Sony','indirizzo' => 'Giappone', 'telefono' => '081931798']),
    ])->keyBy('nome');

    Cache::put('Fornitori', $FornitoriCache, 60);;

    $fornitoriService = new FornitoreService();
    $ordinamento = "";

    expect(fn() => $fornitoriService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});


test('testAllElementsON', function () {

    $FornitoriCache = collect([
        new Fornitore(['nome' => 'AMD','indirizzo' => 'Giappone', 'telefono' => '089343743']),
        new Fornitore(['nome' => 'Nvidia','indirizzo' => 'America', 'telefono' => '089343701']),
        new Fornitore(['nome' => 'Sony','indirizzo' => 'Giappone', 'telefono' => '081931798']),
    ])->keyBy('nome');

    Cache::put('Fornitori', $FornitoriCache, 60);;

    $fornitoriService = new FornitoreService();
    $ordinamento = null;

    expect(fn() => $fornitoriService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});

// ----------------------ALL ELEMENTS----------------------
