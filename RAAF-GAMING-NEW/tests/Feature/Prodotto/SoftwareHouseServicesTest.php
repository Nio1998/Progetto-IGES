<?php

use App\Models\Prodotto\SoftwareHouse;
use App\Services\Prodotto\SoftwareHouseService;
use Database\Seeders\TestSoftwareHouseSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Collection\Collection;

uses()->group('SoftwareHouseUnit', 'Unit');

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
        $schemaFornitore = base_path('tests/resources/init/softwarehouse.sql');
        
        if (file_exists($schemaFornitore)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaFornitore));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaFornitore}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestSoftwareHouseSeeder::class);
        
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
test('testLoadSoftwareHouseDCDID', function () {
    $SoftwareHouseCache = collect([
        new SoftwareHouse(['nomesfh' => 'Activision','logo' => null]),
    ])->keyBy('nomesfh');

    Cache::put('SoftwareHouse', $SoftwareHouseCache, 60);

    $softwarehouse = new SoftwareHouseService();
    $output = $softwarehouse->loadSoftwareHouse();

    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->first()->nomesfh)->toBe('Activision');
    expect($output->first()->logo)->toBe(null);
});


test('testLoadSoftwareHouseDNC', function () {
    $softwarehouse = new SoftwareHouseService();
    $output = $softwarehouse->loadSoftwareHouse();
    
    // Test/Asserzioni
    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->values()[0]->nomesfh)->toBe('Activision');
    expect($output->values()[0]->logo)->toBe(null);
    expect($output->values()[1]->nomesfh)->toBe('CD Project Red');
    expect($output->values()[1]->logo)->toBe(null);
    expect($output->values()[2]->nomesfh)->toBe('Electronic Arts');
    expect($output->values()[2]->logo)->toBe(null);



});

test('testLoadCategorieDNCDNID', function () {
    DB::table('SoftwareHouse')->delete();
    $softwarehouse = new SoftwareHouseService();
    $output = $softwarehouse->loadSoftwareHouse();
    
    // Test/Asserzioni
    expect($output)->toBeEmpty();
});
*/
test('testAllElementsOASC', function () {

    $SoftwareHouseCache = collect([
        new SoftwareHouse(['nomesfh' => 'Activision','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'CD Project Red','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'Electronic Arts','logo' => null]),
    ])->keyBy('nomesfh');

    Cache::put('SoftwareHouse', $SoftwareHouseCache, 60);

    $softwarehouse = new SoftwareHouseService();
    $ordinamento = "nomesfh asc";
    $output =  $softwarehouse->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/SoftwareHouseAsc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testAllElementsODESC', function () {

    $SoftwareHouseCache = collect([
        new SoftwareHouse(['nomesfh' => 'Activision','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'CD Project Red','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'Electronic Arts','logo' => null]),
    ])->keyBy('nomesfh');

    Cache::put('SoftwareHouse', $SoftwareHouseCache, 60);

    $softwarehouse = new SoftwareHouseService();
    $ordinamento = "nomesfh desc";
    $output =  $softwarehouse->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/SoftwareHouseDesc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});


test('testAllElementsONONVAL', function () {

    $SoftwareHouseCache = collect([
        new SoftwareHouse(['nomesfh' => 'Activision','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'CD Project Red','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'Electronic Arts','logo' => null]),
    ])->keyBy('nomesfh');

    Cache::put('SoftwareHouse', $SoftwareHouseCache, 60);

    $softwarehouse = new SoftwareHouseService();
    $ordinamento = "nome sc";

    expect(fn() => $softwarehouse->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Ordinamento scritto in modo errato");
});

test('testAllElementsOV', function () {

    $SoftwareHouseCache = collect([
        new SoftwareHouse(['nomesfh' => 'Activision','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'CD Project Red','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'Electronic Arts','logo' => null]),
    ])->keyBy('nomesfh');

    Cache::put('SoftwareHouse', $SoftwareHouseCache, 60);

    $softwarehouse = new SoftwareHouseService();
    $ordinamento = "";

    expect(fn() => $softwarehouse->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});


test('testAllElementsON', function () {

    $SoftwareHouseCache = collect([
        new SoftwareHouse(['nomesfh' => 'Activision','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'CD Project Red','logo' => null]),
        new SoftwareHouse(['nomesfh' => 'Electronic Arts','logo' => null]),
    ])->keyBy('nomesfh');

    Cache::put('SoftwareHouse', $SoftwareHouseCache, 60);

    $softwarehouse = new SoftwareHouseService();
    $ordinamento = null;

    expect(fn() => $softwarehouse->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});

// ----------------------ALL ELEMENTS----------------------
