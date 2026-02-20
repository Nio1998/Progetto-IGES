<?php

use App\Services\Prodotto\ParteDiService;
use Database\Seeders\TestParteDiSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Prodotto\ParteDi;

uses()->group('ParteDiUnit', 'Unit');

$dbInitialized = false;

// Cleanup del DB
afterAll(function () {
    Schema::dropAllTables();
});

// Inizializzazione Del DB e dei Dati
beforeEach(function () use (&$dbInitialized) {

    if (!$dbInitialized) {
        $schemaParteDi = base_path('tests/resources/init/parteDi.sql');

        if (file_exists($schemaParteDi)) {
            DB::unprepared(file_get_contents($schemaParteDi));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaParteDi}");
        }

        $this->seed(TestParteDiSeeder::class);

        $dbInitialized = true;
    }

    DB::beginTransaction();
});

afterEach(function () {
    DB::rollback();
});


test('ricercaPerChiaveIVSVD', function () {

    $parteDiService = new ParteDiService();

    $output = $parteDiService->ricercaPerChiave(11, 'Avventura');

    $expected = require base_path('tests/resources/expected/ParteDi.php');

    expect($output)->toBeInstanceOf(ParteDi::class);

    foreach ($expected as $campo => $valore) {
        expect($output->$campo)->toBe($valore);
    }
});

test('ricercaPerChiaveIVSVND', function () {

    $parteDiService = new ParteDiService();

    $output = $parteDiService->ricercaPerChiave(11, 'Avve');

    expect($output)->toBeNull();
});

test('ricercaPerChiaveINVRSVD', function () {

    $parteDiService = new ParteDiService();

    $output = $parteDiService->ricercaPerChiave(55, 'Avventura');

    expect($output)->toBeNull();
});

test('ricercaPerChiaveINSVD', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->ricercaPerChiave(-1, 'Avventura'))
        ->toThrow(\InvalidArgumentException::class, 'id1 non valido o null');
});

test('ricercaPerChiaveIVSV', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->ricercaPerChiave(11, ''))
        ->toThrow(\InvalidArgumentException::class, 'id2 null o vuoto');
});

test('ricercaPerChiaveIVSN', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->ricercaPerChiave(11, null))
        ->toThrow(\InvalidArgumentException::class, 'id2 null o vuoto');
});


test('ricercaPerChiaveIDSVD', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->ricercaPerChiave(null, 'Avventura'))
        ->toThrow(\InvalidArgumentException::class, 'id1 non valido o null');
});

test('testAllElementsOASC', function () {

    $parteDiService = new ParteDiService();

    $output = $parteDiService->allElements('videogioco asc');
    $outputArray = $output->values()->all();

    $expected = require base_path('tests/resources/expected/ParteDiAsc.php');

    expect($output)->toHaveCount(count($expected));

    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($outputArray[$index]->$campo)->toBe($valore);
        }
    }
});

test('testAllElementsODESC', function () {

    $parteDiService = new ParteDiService();

    $output = $parteDiService->allElements('videogioco desc');
    $outputArray = $output->values()->all();

    $expected = require base_path('tests/resources/expected/ParteDiDesc.php');

    expect($output)->toHaveCount(count($expected));

    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($outputArray[$index]->$campo)->toBe($valore);
        }
    }
});


test('testAllElementsONONVAL', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->allElements('prova'))
        ->toThrow(\InvalidArgumentException::class, 'ordinamento scritto in modo errato');
});

test('testAllElementsOV', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->allElements(''))
        ->toThrow(\InvalidArgumentException::class, 'ordinamento vuoto o null');
});

test('testAllElementsON', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->allElements(null))
        ->toThrow(\InvalidArgumentException::class, 'ordinamento vuoto o null');
});



test('testNewInsertPINDB', function () {

    $parteDiService = new ParteDiService();

    $item = new ParteDi();
    $item->videogioco = 19;
    $item->categoria  = 'Battle Royale';

    $parteDiService->newInsert($item);

    $expected = require base_path('tests/resources/expected/ParteDiNewInsert.php');

    $actual = DB::table('parte_di')->get()->toArray();

    expect($actual)->toHaveCount(count($expected));

    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }
});


test('testNewInsertPIPDB', function () {

    $parteDiService = new ParteDiService();

    $item = new ParteDi();
    $item->videogioco = 11;
    $item->categoria  = 'Avventura';

    expect(fn() => $parteDiService->newInsert($item))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);
});

test('testNewInsertPINULL', function () {

    $parteDiService = new ParteDiService();

    expect(fn() => $parteDiService->newInsert(null))
        ->toThrow(\TypeError::class);
});