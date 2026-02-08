<?php

use App\Models\Magazzino\PresenteIn;
use App\Services\Magazzino\PresenteInServices;
use Illuminate\Support\Facades\Session;
use Database\Seeders\TestMagazzinoSeeder;
use Database\Seeders\TestPresenteInSeeder;
use Database\Seeders\TestProdottoSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


uses()->group('PresenteInUnit', 'Unit');

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
        $schemaPresenteIn = base_path('tests/resources/init/presentein.sql');
        $schemaMagazzino = base_path('tests/resources/init/magazzino.sql');
        $schemaProdotto = base_path('tests/resources/init/prodotto.sql');
        
       if (file_exists($schemaPresenteIn)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaProdotto));
            DB::unprepared(file_get_contents( $schemaMagazzino));
            DB::unprepared(file_get_contents($schemaPresenteIn));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaPresenteIn}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestMagazzinoSeeder::class);
        $this->seed(TestProdottoSeeder::class);
        $this->seed(TestPresenteInSeeder::class);
        
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

// ----------------------RICERCA PER CHIAVE----------------------
test('testRicercaPerChiaveIVSVD', function () {
    $presenteinservice = new PresenteInServices();
    $output = $presenteinservice->ricercaPerChiave(1, "Italia,Nocera Superiore");

    expect($output)
        ->toBeInstanceOf(PresenteIn::class)
        ->and($output->prodotto)->toBe(1)
        ->and($output->magazzino)->toBe("Italia,Nocera Superiore");
});


test('testRicercaPerChiaveIVSVND', function () {
    $presenteinservice = new PresenteInServices();
    $output = $presenteinservice->ricercaPerChiave(1, "Prova");

    expect($output)->toBeNull();
});

test('testRicercaPerChiaveINVRSVD', function () {
    $presenteinservice = new PresenteInServices();
    $output = $presenteinservice->ricercaPerChiave(100, "Italia,Nocera Superiore");

    expect($output)->toBeNull();
});

test('testRicercaPerChiaveINSVD', function () {
    $presenteinservice = new PresenteInServices();

    expect(fn() => $presenteinservice->ricercaPerChiave(0, "Italia, Nocera Superiore"))
        ->toThrow(\InvalidArgumentException::class, "id1 negativo e/o id2 è null o id2 è stringa vuota");
});

test('testRicercaPerChiaveIVSV', function () {
    $presenteinservice = new PresenteInServices();

    expect(fn() => $presenteinservice->ricercaPerChiave(1, ""))
        ->toThrow(\InvalidArgumentException::class, "id1 negativo e/o id2 è null o id2 è stringa vuota");
});

test('testRicercaPerChiaveIVSN', function () {
    $presenteinservice = new PresenteInServices();

    expect(fn() => $presenteinservice->ricercaPerChiave(1, null))
        ->toThrow(\InvalidArgumentException::class, "id1 negativo e/o id2 è null o id2 è stringa vuota");
});

// ----------------------RICERCA PER CHIAVE----------------------

// ----------------------QUANTITA TOTALE PRODOTTO----------------------
test('testQuantitaTotaleProdottoCPDB', function () {
    $presenteinservice = new PresenteInServices();
    $codiceProdotto = 1;
    
    $output = $presenteinservice->quantitaTotaleProdotto($codiceProdotto);
    
    expect($output)
        ->toBeInt()
        ->toBeGreaterThanOrEqual(0);
    
    expect($output)->toBe(275);
});

test('testQuantitaTotaleProdottoCNPDB', function () {
    $presenteinservice = new PresenteInServices();
    $codiceProdotto = 0;
    
    $output = $presenteinservice->quantitaTotaleProdotto($codiceProdotto);
    
    expect($output)->toBe(0);
});
// ----------------------QUANTITA TOTALE PRODOTTO----------------------

// ----------------------NEW INSERT----------------------
//Bisogna Creare PresenteInNewInsert.php

test('testNewInsertPINDB', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "Italia,Nocera Superiore";
    $item->prodotto = 4;
    $item->quantita_disponibile = 195;
    
    $presenteinservice->newInsert($item);
    
    // Carico i dati attesi dal file expected
    $expected = require base_path('tests/resources/expected/PresenteInNewInsert.php');
    
    // Recupero i dati effettivi dal database
    $actual = DB::table('presente_in')->get()->toArray();
    
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }
});

test('testNewInsertPIPDB', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "Italia,Nocera Superiore";
    $item->prodotto = 1;
    $item->quantita_disponibile = 195;
    
    expect(fn() => $presenteinservice->newInsert($item))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);
});

test('testNewInsertPINULL', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = null;
    
    expect(fn() => $presenteinservice->newInsert($item))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item null");
});
// ----------------------NEW INSERT----------------------

// ----------------------RIFORNITURA----------------------
//QUI BISOGNA CREARE PresenteInRifornituraNonPresente.php

test('testRifornituraPINDB', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "Italia,Prova";
    $item->prodotto = 1;
    $item->quantita_disponibile = 195;
    
    $presenteinservice->rifornitura($item);
    
    $expected = require base_path('tests/resources/expected/PresenteInRifornituraNonPresente.php');

    $actual = DB::table('presente_in')->get()->toArray();
    
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }
});

//QUI BISOGNA CREARE PresenteInRifornituraPresente.php
test('testRifornituraPIPDB', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "Italia,Nocera Superiore";
    $item->prodotto = 1;
    $item->quantita_disponibile = 200;
    
    $presenteinservice->rifornitura($item);
    
    // Carico i dati attesi dal file expected (con la quantità  aggiornata)
    $expected = require base_path('tests/resources/expected/PresenteInRifornituraPresente.php');
    
    // Recupero i dati effettivi dal database
    $actual = DB::table('presente_in')->get()->toArray();
    
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }
});

test('testRifornituraPINULL', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = null;
    
    expect(fn() => $presenteinservice->rifornitura($item))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item null");
});
// ----------------------RIFORNITURA----------------------

// ----------------------DOUPDATE----------------------
//QUI BISOGNA CREARE PresenteInDoUpdate.php

test('testDoUpdateIPQV', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "Italia,Nocera Superiore";
    $item->prodotto = 1;
    $item->quantita_disponibile = 195;
    
    $quantita = 2;
    
    $presenteinservice->doUpdate($item, $quantita);
    
    // Carico i dati attesi dal file expected (con la quantità decrementata)
    $expected = require base_path('tests/resources/expected/PresenteInDoUpdate.php');
    
    // Recupero i dati effettivi dal database
    $actual = DB::table('presente_in')->get()->toArray();
    
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }
});

test('testDoUpdateIPQNV', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "Italia,Nocera Superiore";
    $item->prodotto = 1;
    $item->quantita_disponibile = 195;
    
    $quantita = 0;
    
    expect(fn() => $presenteinservice->doUpdate($item, $quantita))
        ->toThrow(\InvalidArgumentException::class, "La quantità non può essere negativa");
});

test('testDoUpdateIPQM', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "Italia,Nocera Superiore";
    $item->prodotto = 1;
    $item->quantita_disponibile = 195;
    
    $quantita = 1001;
    
    expect(fn() => $presenteinservice->doUpdate($item, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Quantità richiesta maggiore della quantità disponibile");
});

test('testDoUpdateINPQV', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = new PresenteIn();
    $item->magazzino = "prova";
    $item->prodotto = 1;
    $item->quantita_disponibile = 195;
    
    $quantita = 1;
    
    expect(fn() => $presenteinservice->doUpdate($item, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Prodotto non presente nel magazzino specificato");
});

test('testDoUpdateINQV', function () {
    $presenteinservice = new PresenteInServices();
    
    $item = null;
    $quantita = 1;
    
    expect(fn() => $presenteinservice->doUpdate($item, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item null");
});
// ----------------------DOUPDATE----------------------