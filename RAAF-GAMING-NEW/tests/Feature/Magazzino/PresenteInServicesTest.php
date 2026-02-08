<?php

use App\Models\Magazzino\PresenteIn;
use App\Models\Prodotto\Prodotto;
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

//-----------------------ALLELEMENTS-------------------
test('testAllElementsOASC', function () {    
    $presenteinservice = new PresenteInServices();
    $ordinamento = "magazzino asc";
    $output = $presenteinservice->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/PresenteInMagazzinoAsc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }

});

test('testAllElementsODESC', function () {    
    $presenteinservice = new PresenteInServices();
    $ordinamento = "magazzino desc";
    $output = $presenteinservice->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/PresenteInMagazzinoDesc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }

});

test('testAllElementsONONVAL', function () {

    $presenteinservice = new PresenteInServices();
    $ordinamento = "prova desc";

    expect(fn() => $presenteinservice->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "ordinamento non valido");
});


test('testAllElementsOV', function () {
    $presenteinservice = new PresenteInServices();
    $ordinamento = "";

    expect(fn() => $presenteinservice->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "ordinamento vuoto o null");
});

test('testAllElementsON', function () {
    $presenteinservice = new PresenteInServices();
    $ordinamento = null;

    expect(fn() => $presenteinservice->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "ordinamento vuoto o null");
});
// ----------------------MAGAZZINI DA RIFORNIRE----------------------
test('testGetMagazziniDaRifornirePVQVCD', function () {
    $presenteinservice = new PresenteInServices();
    
    // Creo il prodotto con i dati specificati
    $prodotto = Prodotto::where('codice_prodotto',1)->first();
    
    $quantita = 1000;
    
    $output = $presenteinservice->getMagazziniDaRifornire($prodotto, $quantita);
    
    $expected = require base_path('tests/resources/expected/MagazziniDaRifornire.php');
    
    $actual = $output->toArray();
    
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        expect($actual[$index]['magazzino']->indirizzo)->toBe($expectedRow['magazzino']);
        expect($actual[$index]['quantita'])->toBe($expectedRow['quantita']);
        expect($actual[$index]['presente'])->toBe($expectedRow['presente']);
    }
});

test('testGetMagazziniDaRifornirePVQNVCD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = Prodotto::where('codice_prodotto', 1)->first();
    
    $quantita = 0;
    
    // Verifica che venga lanciata un'eccezione InvalidArgumentException
    expect(fn() => $presenteinservice->getMagazziniDaRifornire($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Precondizione non rispettata");
});

test('testGetMagazziniDaRifornirePVQVCS', function () {
    // Creo una situazione inconsistente: prodotti in magazzino superano la capienza
    DB::table('presente_in')->insert([
        'magazzino' => 'Italia,Nocera Superiore',
        'prodotto' => 4,
        'quantita_disponibile' => 900,
    ]);
    
    $presenteinservice = new PresenteInServices();
    
    $prodotto = Prodotto::where('codice_prodotto', 1)->first();
    
    $quantita = 1;
    
    expect(fn() => $presenteinservice->getMagazziniDaRifornire($prodotto, $quantita))
        ->toThrow(\Exception::class, "Qualcosa è andato storto");
});

test('testGetMagazziniDaRifornirePVQVCND', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = Prodotto::where('codice_prodotto', 1)->first();
    
    $quantita = 1456;
    
    $output = $presenteinservice->getMagazziniDaRifornire($prodotto, $quantita);
    
    expect($output)->toBeEmpty();
});

test('testGetMagazziniDaRifornirePNQVCD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = null;
    $quantita = 1;
    
    expect(fn() => $presenteinservice->getMagazziniDaRifornire($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Precondizione non rispettata");
});

test('testGetMagazziniDaRifornirePCPNQVCD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = new Prodotto([
        'prezzo' => 10.5,
        'sconto' => 0,
        'data_uscita' => '2021-12-25',
        'nome' => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura' => '2020-12-20',
        'fornitore' => 'Sony',
        'gestore' => 'prodotto@admin.com',
    ]);
    
    $quantita = 1;
    
    expect(fn() => $presenteinservice->getMagazziniDaRifornire($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Precondizione non rispettata");
});

test('testGetMagazziniDaRifornirePCPVQVCD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = new Prodotto([
        'prezzo' => 10.5,
        'sconto' => 0,
        'data_uscita' => '2021-12-25',
        'nome' => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura' => '2020-12-20',
        'fornitore' => 'Sony',
        'gestore' => 'prodotto@admin.com',
    ]);
    $prodotto->codice_prodotto = "";
    
    $quantita = 1;
    
    expect(fn() => $presenteinservice->getMagazziniDaRifornire($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Precondizione non rispettata");
});
// ----------------------MAGAZZINI DA RIFORNIRE----------------------
// ----------------------GET DISPONIBILITA----------------------
test('testGetDisponibilitaPVQVPD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = Prodotto::where('codice_prodotto', 1)->first();
    
    $quantita = 275;
    
    $output = $presenteinservice->getDisponibilita($prodotto, $quantita);
    
    $expected = require base_path('tests/resources/expected/Disponibilita.php');
    
    $actual = $output->toArray();
    
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        expect($actual[$index]['presente_in']->magazzino)->toBe($expectedRow['magazzino']);
        expect($actual[$index]['presente_in']->prodotto)->toBe($expectedRow['prodotto']);
        expect($actual[$index]['quantita'])->toBe($expectedRow['quantita']);
    }
});

test('testGetDisponibilitaPVQVPND', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = Prodotto::where('codice_prodotto', 1)->first();
    
    $quantita = 276; // Supera la disponibilità totale di 275
    
    $output = $presenteinservice->getDisponibilita($prodotto, $quantita);
    
    expect($output)->toBeEmpty();
    expect($output)->toHaveCount(0);
});

test('testGetDisponibilitaPVQNVPD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = Prodotto::where('codice_prodotto', 1)->first();
    
    $quantita = 0;
    
    expect(fn() => $presenteinservice->getDisponibilita($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "La quantità da acquistare deve essere un numero positivo.");
});

test('testGetDisponibilitaPNQVPD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = null;
    $quantita = 1;
    
    expect(fn() => $presenteinservice->getDisponibilita($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Inserire un prodotto valido.");
});

test('testGetDisponibilitaPCPNQVPD', function () {
    $presenteinservice = new PresenteInServices();
    
    $prodotto = new Prodotto([
        'prezzo' => 10.5,
        'sconto' => 0,
        'data_uscita' => '2021-12-25',
        'nome' => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura' => '2020-12-20',
        'fornitore' => 'Sony',
        'gestore' => 'prodotto@admin.com',
    ]);

    $quantita = 1;
    
    expect(fn() => $presenteinservice->getDisponibilita($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Inserire un prodotto valido.");
});

test('testGetDisponibilitaPCPVQVPD', function () {
    $presenteinservice = new PresenteInServices();

    $prodotto = new Prodotto([
        'prezzo' => 10.5,
        'sconto' => 0,
        'data_uscita' => '2021-12-25',
        'nome' => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura' => '2020-12-20',
        'fornitore' => 'Sony',
        'gestore' => 'prodotto@admin.com',
    ]);
    $prodotto->codice_prodotto = "";
    
    $quantita = 1;
    
    expect(fn() => $presenteinservice->getDisponibilita($prodotto, $quantita))
        ->toThrow(\InvalidArgumentException::class, "Inserire un prodotto valido.");
});
// ----------------------GET DISPONIBILITA----------------------