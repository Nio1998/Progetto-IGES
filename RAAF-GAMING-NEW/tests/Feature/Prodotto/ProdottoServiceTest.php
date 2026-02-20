<?php

use App\Models\Magazzino\PresenteIn;
use App\Models\Prodotto\Abbonamento;
use App\Models\Prodotto\Fornitore;
use App\Models\Prodotto\Prodotto;
use App\Models\Prodotto\Recensisce;
use App\Models\Prodotto\Videogioco;
use App\Models\Profilo\Cliente;
use App\Services\Prodotto\ProdottoService;
use Database\Seeders\TestClienteSeeder;
use Database\Seeders\TestFornitoreSeeder;
use Database\Seeders\TestPresenteInSeeder;
use Database\Seeders\TestProdottoSeeder;
use Database\Seeders\TestRecensisceSeeder;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

uses()->group('ProdottoUnit', 'Unit');

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
        $schemaProdotto = base_path('tests/resources/init/prodotto.sql');
        $schemaCliente = base_path('tests/resources/init/cliente.sql');
        $schemaPesentein = base_path('tests/resources/init/presentein.sql');
        $schemaFornitore = base_path('tests/resources/init/fornitore.sql');
        $schemaRecensisce = base_path('tests/resources/init/recensisce.sql');
        $schemaVideogioco = base_path('tests/resources/init/videogioco.sql');
        $schemaAbbonamento = base_path('tests/resources/init/abbonamento.sql');
        $schemaDlc = base_path('tests/resources/init/dlc.sql');
        $schemaConsole = base_path('tests/resources/init/console.sql');        
        
       if (file_exists($schemaProdotto)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaProdotto));
            DB::unprepared(file_get_contents($schemaCliente));
            DB::unprepared(file_get_contents($schemaPesentein));
            DB::unprepared(file_get_contents($schemaFornitore));
            DB::unprepared(file_get_contents($schemaRecensisce));
            DB::unprepared(file_get_contents($schemaVideogioco));
            DB::unprepared(file_get_contents($schemaAbbonamento));
            DB::unprepared(file_get_contents($schemaDlc));
            DB::unprepared(file_get_contents($schemaConsole));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaProdotto}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestProdottoSeeder::class);
        $this->seed(TestClienteSeeder::class);
        $this->seed(TestPresenteInSeeder::class);
        $this->seed(TestFornitoreSeeder::class);
        $this->seed(TestRecensisceSeeder::class);

        $dbInitialized = true;
    }
    
    // Inizia transazione per ogni test
    DB::beginTransaction();
    Session::flush();
    Cache::flush();
});

afterEach(function () {
    DB::rollback();
    Cache::flush();
});

// ----------------------RICERCA PER CHIAVE----------------------
test('testRicercaPerChiaveCPDB', function () {

    $prodottoService = new ProdottoService();
    $output = $prodottoService->ricercaPerChiave("1");

    expect($output)
        ->toBeInstanceOf(Prodotto::class)
        ->and($output->codice_prodotto)->toBe(1)
        ->and($output->prezzo)->toBe(10.5)
        ->and((int)$output->sconto)->toBe(0)
        ->and($output->data_uscita->format('Y-m-d'))->toBe('2021-12-25')
        ->and($output->nome)->toBe('FIFA')
        ->and($output->quantita_fornitura)->toBe(12)
        ->and($output->data_fornitura->format('Y-m-d'))->toBe('2020-12-20')
        ->and($output->fornitore)->toBe('Sony')
        ->and($output->gestore)->toBe('prodotto@admin.com')
        ->and($output->prezzo_effettivo)->toBe(10.5)
        ->and($output->disponibile)->toBeTrue()
        ->and($output->necessita_rifornimento)->toBeFalse();  
});

test('testRicercaPerChiaveCNPDB', function () {

    $prodottoService = new ProdottoService();
    $output = $prodottoService->ricercaPerChiave("1000");

    expect($output)->toBeNull();
});

test('testRicercaPerChiaveCN', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->ricercaPerChiave(null))
        ->toThrow(\InvalidArgumentException::class, "Codice prodotto null o vuoto");
});

test('testRicercaPerChiaveCV', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->ricercaPerChiave(""))
        ->toThrow(\InvalidArgumentException::class, "Codice prodotto null o vuoto");
});
// ----------------------RICERCA PER CHIAVE----------------------

// ----------------------RICERCA PER SOTTOSTRINGA----------------------
test('testRicercaPerSottostringaNPDB', function () {

    $prodottoService = new ProdottoService();
    $output = $prodottoService->ricercaPerSottostringa("FIF");

    expect($output)
        ->toBeInstanceOf(Collection::class)
        ->and($output->count())->toBeGreaterThanOrEqual(1);

    $prodotto = $output->firstWhere('nome', 'FIFA');

    expect($prodotto)->not->toBeNull()
        ->and($prodotto->codice_prodotto)->toBe(1)
        ->and($prodotto->nome)->toBe('FIFA')
        ->and($prodotto->prezzo)->toBe(10.5)
        ->and($prodotto->prezzo_effettivo)->toBe(10.5)
        ->and($prodotto->disponibile)->toBeTrue()
        ->and($prodotto->in_promozione)->toBeFalse();
});

test('testRicercaPerSottostringaNNPDB', function () {

    $prodottoService = new ProdottoService();
    $output = $prodottoService->ricercaPerSottostringa("GIOCO");

    expect($output)
        ->toBeInstanceOf(Collection::class)
        ->and($output->count())->toBe(0);
});

test('testRicercaPerSottostringaNN', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->ricercaPerSottostringa(null))
        ->toThrow(\InvalidArgumentException::class, "Nome null");
});
// ----------------------RICERCA PER SOTTOSTRINGA----------------------

// ----------------------RICERCA PER NOME----------------------
test('testRicercaPerNomeNPDB', function () {

    $prodottoService = new ProdottoService();
    $output = $prodottoService->ricercaPerNome("FIFA");

    expect($output)
        ->toBeInstanceOf(Prodotto::class)
        ->and($output->nome)->toBe('FIFA')
        ->and($output->codice_prodotto)->toBe(1)
        ->and($output->prezzo)->toBe(10.5)
        ->and($output->quantita_fornitura)->toBe(12)
        ->and($output->data_fornitura->format('Y-m-d'))->toBe('2020-12-20')
        ->and($output->fornitore)->toBe('Sony')
        ->and($output->necessita_rifornimento)->toBeFalse()
        ->and($output->giorni_data_fornitura)->toBeFloat();
});

test('testRicercaPerNomeNNPDB', function () {

    $prodottoService = new ProdottoService();
    $output = $prodottoService->ricercaPerNome("GIOCO");

    expect($output)->toBeNull();
});

test('testRicercaPerNomeNN', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->ricercaPerNome(null))
        ->toThrow(\InvalidArgumentException::class, "Nome null");
});
// ----------------------RICERCA PER NOME----------------------

// ----------------------GET MAX----------------------
test('testGetMaxDB', function () {

    $prodottoService = new ProdottoService();
    $output = $prodottoService->getMax();

    expect($output)->toBeInt()->and($output)->toBe(5);
});

test('testGetMaxVuotoDB', function () {

    DB::table('recensisce')->delete();
    DB::table('presente_in')->delete();
    DB::table('videogioco')->delete();
    DB::table('abbonamento')->delete();
    DB::table('dlc')->delete();
    DB::table('console')->delete();
    DB::table('cliente')->delete();
    DB::table('fornitore')->delete();
    DB::table('prodotto')->delete();
    
    $prodottoService = new ProdottoService();
    $output = $prodottoService->getMax();

    expect($output)->toBe(1);
});
// ----------------------GET MAX----------------------

// ----------------------ALL ELEMENTS----------------------
test('testAllElementsOASC', function () {

    $prodottoService = new ProdottoService();
    $ordinamento = "codice_prodotto asc";
    $output = $prodottoService->allElements($ordinamento);
    $output->toArray();
    $expected = require base_path('tests/resources/expected/ProdottoCodiceAsc.php');

    expect($output)->toHaveCount(count($expected));

    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $output[$index]->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
})->group('Regression');

test('testAllElementsODESC', function () {

    $prodottoService = new ProdottoService();
    $ordinamento = "codice_prodotto desc";
    $output = $prodottoService->allElements($ordinamento);
    $output->toArray();
    $expected = require base_path('tests/resources/expected/ProdottoCodiceDesc.php');

    expect($output)->toHaveCount(count($expected));

    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $output[$index]->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
})->group('Regression');

test('testAllElementsONONVAL', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->allElements("salve"))
        ->toThrow(\InvalidArgumentException::class, "ordinamento scritto in modo errato");
})->group('Regression');

test('testAllElementsOV', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->allElements(""))
        ->toThrow(\InvalidArgumentException::class, "ordinamento e' null o stringa vuota");
})->group('Regression');

test('testAllElementsON', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->allElements(null))
        ->toThrow(\InvalidArgumentException::class, "ordinamento e' null o stringa vuota");
})->group('Regression');
// ----------------------ALL ELEMENTS----------------------

// ----------------------GET TOP6HOME----------------------
test('testGetTop6HomesCV', function () {

    $prodottiFittizi = collect([
        ['codice_prodotto' => 1, 'nome' => 'FIFA',          'prezzo' => 10.5,  'sconto' => '0',  'data_uscita' => '2021-12-25', 'prezzo_effettivo' => 10.5,  'disponibile' => true, 'in_promozione' => false],
        ['codice_prodotto' => 2, 'nome' => 'PES',           'prezzo' => 15.5,  'sconto' => '0',  'data_uscita' => '2020-12-22', 'prezzo_effettivo' => 15.5,  'disponibile' => true, 'in_promozione' => false],
        ['codice_prodotto' => 3, 'nome' => 'Elden Ring',    'prezzo' => 59.99, 'sconto' => '10', 'data_uscita' => '2022-03-15', 'prezzo_effettivo' => 53.99, 'disponibile' => true, 'in_promozione' => true],
        ['codice_prodotto' => 4, 'nome' => 'Expedition 33', 'prezzo' => 49.99, 'sconto' => '20', 'data_uscita' => '2026-03-15', 'prezzo_effettivo' => 39.99, 'disponibile' => true, 'in_promozione' => true],
        ['codice_prodotto' => 5, 'nome' => 'God of War',    'prezzo' => 39.99, 'sconto' => '15', 'data_uscita' => '2021-01-10', 'prezzo_effettivo' => 33.99, 'disponibile' => true, 'in_promozione' => true],
        ['codice_prodotto' => 6, 'nome' => 'Spider-Man',    'prezzo' => 29.99, 'sconto' => '25', 'data_uscita' => '2020-05-10', 'prezzo_effettivo' => 22.49, 'disponibile' => true, 'in_promozione' => true],
    ]);

    Cache::put('top6_home', $prodottiFittizi, now()->addMinutes(30));

    $prodottoService = new ProdottoService();
    $output = $prodottoService->getTop6Home();

    expect($output)->toBeInstanceOf(Collection::class)
        ->and($output)->toHaveCount(6)
        ->and($output)->toBe($prodottiFittizi);
});

test('testGetTop6HomeCNVRSPSSS', function () {

    DB::table('prodotto')->insert([
        ['codice_prodotto' => 5, 'prezzo' => 39.99, 'sconto' => 15, 'data_uscita' => '2021-01-10', 'nome' => 'God of War',  'quantita_fornitura' => 10, 'data_fornitura' => '2021-01-01', 'fornitore' => 'Sony', 'gestore' => 'prodotto@admin.com'],
        ['codice_prodotto' => 6, 'prezzo' => 29.99, 'sconto' => 25, 'data_uscita' => '2020-05-10', 'nome' => 'Spider-Man', 'quantita_fornitura' => 8,  'data_fornitura' => '2020-05-01', 'fornitore' => 'Sony', 'gestore' => 'prodotto@admin.com'],
        ['codice_prodotto' => 7, 'prezzo' => 19.99, 'sconto' => 30, 'data_uscita' => '2019-05-10', 'nome' => 'Minecraft',  'quantita_fornitura' => 5,  'data_fornitura' => '2019-05-01', 'fornitore' => 'Sony', 'gestore' => 'prodotto@admin.com'],
    ]);

    DB::table('videogioco')->insert([
        ['prodotto' => 2, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
        ['prodotto' => 3, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
        ['prodotto' => 4, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
        ['prodotto' => 5, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
        ['prodotto' => 6, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
        ['prodotto' => 7, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
    ]);

    Cache::forget('top6_home');

    $prodottoService = new ProdottoService();
    $output = $prodottoService->getTop6Home();
    $output->toArray();
    $expected = require base_path('tests/resources/expected/ProdottoTop6HomeDB.php');

    expect($output)->toHaveCount(count($expected));

    foreach ($expected as $expectedRow) {
        $prodotto = $output->firstWhere('codice_prodotto', $expectedRow['codice_prodotto']);
        expect($prodotto)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $prodotto->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testGetTop6HomeCNVRSPSSN', function () {

    DB::table('videogioco')->insert([
        ['prodotto' => 2, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
        ['prodotto' => 3, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
        ['prodotto' => 4, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
    ]);

    Cache::forget('top6_home');

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->getTop6Home())
        ->toThrow(\RuntimeException::class, "Non ci sono abbastanza prodotti scontati");
});

test('testGetTop6HomeCNVRN', function () {

    DB::table('recensisce')->delete();
    Cache::forget('top6_home');

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->getTop6Home())
        ->toThrow(\RuntimeException::class, "Nessuna recensione disponibile");
});

test('testGetTop6HomeCNVRSPN', function () {

    DB::table('videogioco')->insert([
        ['prodotto' => 2, 'dimensione' => 10.5, 'pegi' => 18, 'edizione_limitata' => 0, 'ncd' => null, 'vkey' => null, 'software_house' => null],
    ]);

    Cache::forget('top6_home');

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->getTop6Home())
        ->toThrow(\RuntimeException::class, "Nessun altro videogioco disponibile");
});
// ----------------------GET TOP6HOME----------------------

// ----------------------NEW INSERT----------------------
test('testNewInsertPN', function () {

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->newInsert(null))
        ->toThrow(\InvalidArgumentException::class, "Il prodotto inserito è null");
});

test('testNewInsertPNDRV', function () {

    $prodotto = new Prodotto();
    $prodotto->codice_prodotto = 100;
    $prodotto->prezzo = 80.00;
    $prodotto->sconto = 0;
    $prodotto->data_uscita = '2024-02-02';
    $prodotto->nome = 'Death Stranding 2';
    $prodotto->quantita_fornitura = 50;
    $prodotto->data_fornitura = '2024-02-02';
    $prodotto->fornitore = 'Sony';
    $prodotto->gestore = 'prodotto@admin.com';

    $videogioco = new Videogioco();
    $videogioco->dimensione = 50;
    $videogioco->pegi = 18;
    $videogioco->edizione_limitata = false;
    $videogioco->ncd = 1;
    $videogioco->software_house = 'Activision';

    $prodotto->setRelation('videogioco', $videogioco);

    Cache::put('top6_home', collect([1,2,3]), now()->addMinutes(30));

    $prodottoService = new ProdottoService();
    $prodottoService->newInsert($prodotto);

    $expected = require base_path('tests/resources/expected/ProdottoNewInsert.php');
    $output = Prodotto::all();

    expect($output)->toHaveCount(count($expected));

    foreach ($expected as $expectedRow) {
        $p = $output->firstWhere('codice_prodotto', $expectedRow['codice_prodotto']);
        expect($p)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            expect($p->$campo)->toBe($valore);
        }
    }

    expect(Cache::get('top6_home'))->toBeNull();
});

test('testNewInsertPNDRNV', function () {

    $prodotto = new Prodotto();
    $prodotto->codice_prodotto = 100;
    $prodotto->prezzo = 80.00;
    $prodotto->sconto = 0;
    $prodotto->data_uscita = '2024-02-02';
    $prodotto->nome = 'Death Stranding 2';
    $prodotto->quantita_fornitura = 50;
    $prodotto->data_fornitura = '2024-02-02';
    $prodotto->fornitore = 'Sony';
    $prodotto->gestore = 'prodotto@admin.com';

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->newInsert($prodotto))
        ->toThrow(\InvalidArgumentException::class, "Il prodotto deve avere esattamente una specializzazione");
});

test('testNewInsertPNDRT', function () {

    $prodotto = new Prodotto();
    $prodotto->codice_prodotto = 100;
    $prodotto->prezzo = 80.00;
    $prodotto->sconto = 0;
    $prodotto->data_uscita = '2024-02-02';
    $prodotto->nome = 'Death Stranding 2';
    $prodotto->quantita_fornitura = 50;
    $prodotto->data_fornitura = '2024-02-02';
    $prodotto->fornitore = 'Sony';
    $prodotto->gestore = 'prodotto@admin.com';

    $videogioco = new Videogioco();
    $videogioco->dimensione = 50;
    $videogioco->pegi = 18;
    $videogioco->edizione_limitata = false;
    $videogioco->ncd = 1;
    $videogioco->software_house = 'Activision';

    $abbonamento = new Abbonamento();

    $prodotto->setRelation('videogioco', $videogioco);
    $prodotto->setRelation('abbonamento', $abbonamento);

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->newInsert($prodotto))
        ->toThrow(\InvalidArgumentException::class, "Il prodotto deve avere esattamente una specializzazione");
});

test('testNewInsertPPRV', function () {

    $prodotto = new Prodotto();
    $prodotto->codice_prodotto = 1;
    $prodotto->prezzo = 80.00;
    $prodotto->sconto = 0;
    $prodotto->data_uscita = '2024-02-02';
    $prodotto->nome = 'Death Stranding 2';
    $prodotto->quantita_fornitura = 50;
    $prodotto->data_fornitura = '2024-02-02';
    $prodotto->fornitore = 'Sony';
    $prodotto->gestore = 'prodotto@admin.com';

    $videogioco = new Videogioco();
    $videogioco->dimensione = 50;
    $videogioco->pegi = 18;
    $videogioco->edizione_limitata = false;
    $videogioco->ncd = 1;
    $videogioco->software_house = 'Activision';

    $prodotto->setRelation('videogioco', $videogioco);

    $prodottoService = new ProdottoService();

    expect(fn() => $prodottoService->newInsert($prodotto))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);
});
// ----------------------NEW INSERT----------------------

// ----------------------DO UPDATE----------------------

// ----------------------DO UPDATE----------------------
