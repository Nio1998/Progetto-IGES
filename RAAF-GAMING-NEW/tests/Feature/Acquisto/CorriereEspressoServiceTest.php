<?php

use App\Models\Acquisto\CorriereEspresso;
use App\Services\Acquisto\CorriereEspressoService;
use Database\Seeders\TestCorriereEspressoSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Collection\Collection;

uses()->group('CorriereEspressoUnit', 'Unit');

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
        $schemaCorriereEspresso = base_path('tests/resources/init/corriereespresso.sql');
        
        if (file_exists($schemaCorriereEspresso)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaCorriereEspresso));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaCorriereEspresso}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestCorriereEspressoSeeder::class);
        
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
/* PER ESSERE ATTIVATO DOBBIMO CAMBIARE IL METODO LoadCorrieri DA PRIVATE A PUBLIC
test('testLoadCorrieriDCDID', function () {
    $CorrieriCache = collect([
        new CorriereEspresso(['nome' => 'bartolini', 'sito' => 'bartolini.com']),
    ])->keyBy('nome');

    Cache::put('Corrieri', $CorrieriCache, 60);

    $corrieri = new CorriereEspressoService();
    $output = $corrieri->loadCorrieri();
    
    // Test/Asserzioni
    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->first()->nome)->toBe('bartolini');
    expect($output->first()->sito)->toBe('bartolini.com');

});

test('testLoadCorrieriDNCDID', function () {
    $corrieri = new CorriereEspressoService();
    $output = $corrieri->loadCorrieri();
    
    // Test/Asserzioni
    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->values()[0]->nome)->toBe('bartolini');
    expect($output->values()[0]->sito)->toBe('bartolini.com');
    expect($output->values()[1]->nome)->toBe('ups');
    expect($output->values()[1]->sito)->toBe('ups.com');
    expect($output->values()[2]->nome)->toBe('dhl');
    expect($output->values()[2]->sito)->toBe('dhl.com');
    expect($output->values()[3]->nome)->toBe('lol');
    expect($output->values()[3]->sito)->toBe('lol.com');

});

test('testLoadCorrieriDNCDNID', function () {
    DB::table('corriereespresso')->delete();
    $corrieri = new CorriereEspressoService();
    $output = $corrieri->loadCorrieri();
    
    // Test/Asserzioni
    expect($output)->toBeEmpty();
});
*/

test('testAllElementsOASC', function () {

    $Corrieri = collect([
    new CorriereEspresso(['nome' => 'bartolini', 'sito' => 'bartolini.com']),
    new CorriereEspresso(['nome' => 'dhl',       'sito' => 'dhl.com']),
    new CorriereEspresso(['nome' => 'lol',       'sito' => 'lol.com']),
    new CorriereEspresso(['nome' => 'ups',       'sito' => 'ups.com']),
    ])->keyBy('nome');

    Cache::put('Corrieri', $Corrieri, 60);

    $corrieriService = new CorriereEspressoService();
    $ordinamento = "nome asc";
    $output = $corrieriService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/CorriereEspressoNomeAsc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testAllElementsODESC', function () {

    $Corrieri = collect([
    new CorriereEspresso(['nome' => 'bartolini', 'sito' => 'bartolini.com']),
    new CorriereEspresso(['nome' => 'dhl',       'sito' => 'dhl.com']),
    new CorriereEspresso(['nome' => 'lol',       'sito' => 'lol.com']),
    new CorriereEspresso(['nome' => 'ups',       'sito' => 'ups.com']),
    ])->keyBy('nome');

    Cache::put('Corrieri', $Corrieri, 60);

    $corrieriService = new CorriereEspressoService();
    $ordinamento = "nome desc";
    $output = $corrieriService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/CorriereEspressoNomeDesc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }

});


test('testAllElementsONONVAL', function () {

    $Corrieri = collect([
    new CorriereEspresso(['nome' => 'bartolini', 'sito' => 'bartolini.com']),
    new CorriereEspresso(['nome' => 'dhl',       'sito' => 'dhl.com']),
    new CorriereEspresso(['nome' => 'lol',       'sito' => 'lol.com']),
    new CorriereEspresso(['nome' => 'ups',       'sito' => 'ups.com']),
    ])->keyBy('nome');

    Cache::put('Corrieri', $Corrieri, 60);

    $corrieriService = new CorriereEspressoService();
    $ordinamento = "nome as";

    expect(fn() => $corrieriService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Ordinamento scritto in modo errato");
});

test('testAllElementsOV', function () {

    $Corrieri = collect([
    new CorriereEspresso(['nome' => 'bartolini', 'sito' => 'bartolini.com']),
    new CorriereEspresso(['nome' => 'dhl',       'sito' => 'dhl.com']),
    new CorriereEspresso(['nome' => 'lol',       'sito' => 'lol.com']),
    new CorriereEspresso(['nome' => 'ups',       'sito' => 'ups.com']),
    ])->keyBy('nome');

    Cache::put('Corrieri', $Corrieri, 60);

    $corrieriService = new CorriereEspressoService();
    $ordinamento = "";

    expect(fn() => $corrieriService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});


test('testAllElementsON', function () {

    $Corrieri = collect([
    new CorriereEspresso(['nome' => 'bartolini', 'sito' => 'bartolini.com']),
    new CorriereEspresso(['nome' => 'dhl',       'sito' => 'dhl.com']),
    new CorriereEspresso(['nome' => 'lol',       'sito' => 'lol.com']),
    new CorriereEspresso(['nome' => 'ups',       'sito' => 'ups.com']),
    ])->keyBy('nome');

    Cache::put('Corrieri', $Corrieri, 60);

    $corrieriService = new CorriereEspressoService();
    $ordinamento = null;

    expect(fn() => $corrieriService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});

// ----------------------ALL ELEMENTS----------------------
