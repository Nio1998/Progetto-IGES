<?php

use App\Models\Magazzino\Magazzino;
use App\Services\Magazzino\MagazzinoService;
use Illuminate\Support\Facades\Session;
use Database\Seeders\TestMagazzinoSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Collection\Collection;

uses()->group('MagazzinoUnit', 'Unit');

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
        $schemaMagazzino = base_path('tests/resources/init/magazzino.sql');
        
        if (file_exists($schemaMagazzino)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaMagazzino));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaMagazzino}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestMagazzinoSeeder::class);
        
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
test('testLoadMagazzinoDCDID', function () {
    $magazziniCache = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazziniCache, 60);

    $magazzini = new MagazzinoService();
    $output = $magazzini->loadMagazzini();
    
    // Test/Asserzioni
    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->first()->indirizzo)->toBe('Italia,Nocera Superiore');
    expect($output->first()->capienza)->toBe(1000);

});

test('testLoadMagazzinoDNCDID', function () {
    $magazzini = new MagazzinoService();
    $output = $magazzini->loadMagazzini();
    
    // Test/Asserzioni
    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->values()[0]->indirizzo)->toBe('Italia,Nocera Superiore');
    expect($output->values()[0]->capienza)->toBe(1000);
    expect($output->values()[1]->indirizzo)->toBe('Italia,Solofra');
    expect($output->values()[1]->capienza)->toBe(1000);

});

test('testLoadMagazzinoDNCDNID', function () {
    DB::table('magazzino')->delete();
    $magazzini = new MagazzinoService();
    $output = $magazzini->loadMagazzini();
    
    // Test/Asserzioni
    expect($output)->toBeEmpty();
});
*/

// ----------------------RICERCA PER CHIAVE----------------------
test('testRicercaPerChiaveSPC', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoservice = new MagazzinoService();
    $output = $magazzinoservice->ricercaPerChiave("Italia,Nocera Superiore");

    expect($output)
    ->toBeInstanceOf(Magazzino::class)
    ->and($output->indirizzo)->toBe("Italia,Nocera Superiore")
    ->and($output->capienza)->toBe(1000);
});

test('testRicercaPerChiaveSNPC', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoservice = new MagazzinoService();
    $output = $magazzinoservice->ricercaPerChiave("Italia,Nola");

    expect($output)->toBeNull();
});

test('testRicercaPerChiaveSV', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoservice = new MagazzinoService();

    expect(fn() => $magazzinoservice->ricercaPerChiave(""))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testRicercaPerChiaveSN', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoservice = new MagazzinoService();

    expect(fn() => $magazzinoservice->ricercaPerChiave(null))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});
// ----------------------RICERCA PER CHIAVE----------------------
test('testAllElementsOASC', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoService = new MagazzinoService();
    $ordinamento = "indirizzo asc";
    $output = $magazzinoService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/MagazzinoIndirizzoAsc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});
test('testAllElementsODESC', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoService = new MagazzinoService();
    $ordinamento = "indirizzo desc";
    $output = $magazzinoService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/MagazzinoIndirizzoDesc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }

});


test('testAllElementsONONVAL', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoService = new MagazzinoService();
    $ordinamento = "prova dasc";

    expect(fn() => $magazzinoService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Ordinamento scritto in modo errato");
});
test('testAllElementsOV', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoService = new MagazzinoService();
    $ordinamento = "";

    expect(fn() => $magazzinoService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});


test('testAllElementsON', function () {

    $magazzini = collect([
        new Magazzino(['indirizzo' => 'Italia,Nocera Superiore', 'capienza' => 1000]),
        new Magazzino(['indirizzo' => 'Italia,Solofra', 'capienza' => 1000])
    ])->keyBy('indirizzo');

    Cache::put('Magazzini', $magazzini, 60);

    $magazzinoService = new MagazzinoService();
    $ordinamento = null;

    expect(fn() => $magazzinoService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});

// ----------------------ALL ELEMENTS----------------------
