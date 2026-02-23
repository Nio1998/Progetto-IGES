<?php

use App\Models\Acquisto\Ordine;
use App\Models\Acquisto\Riguarda;
use App\Models\Acquisto\Spedito;
use App\Services\Acquisto\OrdineService;
use Database\Seeders\TestOrdineSeeder;
use Database\Seeders\TestRiguardaSeeder;
use Database\Seeders\TestSpeditoSeeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses()->group('OrdineUnit', 'Unit');

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
        $schemaOrdine = base_path('tests/resources/init/ordine.sql');
        $schemaSpedito = base_path('tests/resources/init/spedito.sql');
        $schemaRiguarda = base_path('tests/resources/init/riguarda.sql'); 
        
       if (file_exists($schemaOrdine)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaOrdine));
            DB::unprepared(file_get_contents($schemaSpedito));
            DB::unprepared(file_get_contents($schemaRiguarda));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaOrdine}");
        }
        
        // Esegui SOLO i seeder che ti servono
        $this->seed(TestOrdineSeeder::class);
        $this->seed(TestSpeditoSeeder::class);
        $this->seed(TestRiguardaSeeder::class);

        $dbInitialized = true;
    }
    
    // Inizia transazione per ogni test
    DB::beginTransaction();
});

afterEach(function () {
    DB::rollback();
});

// ----------------------GET ORDINI NON CONSEGNATI----------------------
test('testGetOrdiniNonConsegnatiOP', function () {

    $ordineService = new OrdineService();
    $output = $ordineService->getOrdiniNonConsegnati();

    expect($output)
        ->toBeInstanceOf(Collection::class)
        ->and($output->count())->toBeGreaterThan(0);

    foreach ($output as $ordine) {
        expect($ordine->gestore)->toBeNull();
    }
});

test('testGetOrdiniNonConsegnatiON', function () {

    DB::table('ordine')->whereNull('gestore')->delete();

    $ordineService = new OrdineService();
    $output = $ordineService->getOrdiniNonConsegnati();

    expect($output)
        ->toBeInstanceOf(Collection::class)
        ->and($output->count())->toBe(0);
});
// ----------------------GET ORDINI NON CONSEGNATI----------------------

// ----------------------GENERA CODICE ORDINE----------------------
/* DA DECOMMENTARE SOLO QUANDO SI VUOLE TESTARE QUELLA FUNZIONE I QUESTO CASO,
SI RICORDA DI COMMENTARE POI LA FUNZIONE ORIGINALE E DECOMMENTARE QUELLA DA TESTARE
test('testGeneraCodiceOrdineCID', function () {

    $codiceEsistente = (string) DB::table('ordine')->value('codice');
    $codicNuovo = '999999999';

    $tentativi = 0;
    $randFn = function () use ($codiceEsistente, $codicNuovo, &$tentativi) {
        $tentativi++;
        if ($tentativi === 1) {
            return $codiceEsistente; // primo tentativo → duplicato
        }
        return $codicNuovo; // secondo tentativo → univoco
    };

    $ordineService = new OrdineService();
    $output = $ordineService->generaCodiceOrdine($randFn);

    expect($tentativi)->toBe(2)
        ->and($output)->toBe($codicNuovo);
}); */

test('testGeneraCodiceOrdineCND', function () {

    $ordineService = new OrdineService();
    $output = $ordineService->generaCodiceOrdine();

    expect($output)
        ->toBeString()
        ->and(strlen($output))->toBe(9)
        ->and(DB::table('ordine')->where('codice', $output)->exists())->toBeFalse();
});
// ----------------------GENERA CODICE ORDINE----------------------

// ----------------------NEW INSERT----------------------
test('testNewInsertONPRVV', function () {

    $ordine = new Ordine();
    $ordine->codice = '15280754013';
    $ordine->data_acquisto = '2019-11-30';
    $ordine->indirizzo_di_consegna = 'viale croce';
    $ordine->cliente = 'a.maddaloni25@gmail.com';
    $ordine->prezzo_totale = 80;
    $ordine->stato = 'elaborazione';
    $ordine->metodo_di_pagamento = '2134567891234567';

    $riguarda = new Riguarda();
    $riguarda->prodotto = 1;
    $riguarda->quantita_acquistata = 1;

    $riguardaList = collect([$riguarda]);

    $ordineService = new OrdineService();
    $ordineService->newInsert($ordine, $riguardaList);

    $expectedOrdini = require base_path('tests/resources/expected/OrdineNewInsert.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore); // ✅ usa $actualValue non $o->$campo
        }
    }

    $expectedRiguarda = require base_path('tests/resources/expected/RiguardaNewInsert.php');
    $outputRiguarda = Riguarda::all();

    expect($outputRiguarda)->toHaveCount(count($expectedRiguarda));

    foreach ($expectedRiguarda as $expectedRow) {
        $r = $outputRiguarda->first(fn($item) => $item->prodotto == $expectedRow['prodotto'] && $item->ordine == $expectedRow['ordine']);
        expect($r)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            expect($r->$campo)->toBe($valore);
        }
    }
});

test('testNewInsertOPRVV', function () {

    $ordine = new Ordine();
    $ordine->codice = '26134054612';
    $ordine->data_acquisto = '2021-12-30';
    $ordine->indirizzo_di_consegna = 'viale croce';
    $ordine->cliente = 'f.peluso25@gmail.com';
    $ordine->prezzo_totale = 80.5;
    $ordine->gestore = 'ordine@admin.com';
    $ordine->stato = 'spedito';
    $ordine->metodo_di_pagamento = '2134567891234567';

    $riguarda = new Riguarda();
    $riguarda->prodotto = 1;
    $riguarda->quantita_acquistata = 1;

    $riguardaList = collect([$riguarda]);

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->newInsert($ordine, $riguardaList))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);

    $expectedOrdini = require base_path('tests/resources/expected/Ordine.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testNewInsertONRVV', function () {

    $riguarda = new Riguarda();
    $riguarda->prodotto = 1;
    $riguarda->quantita_acquistata = 1;

    $riguardaList = collect([$riguarda]);

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->newInsert(null, $riguardaList))
        ->toThrow(\InvalidArgumentException::class, "L'ordine è null");

    $expectedOrdini = require base_path('tests/resources/expected/Ordine.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testNewInsertONPRV', function () {

    $ordine = new Ordine();
    $ordine->codice = '15280754013';
    $ordine->data_acquisto = '2019-11-30';
    $ordine->indirizzo_di_consegna = 'viale croce';
    $ordine->cliente = 'a.maddaloni25@gmail.com';
    $ordine->prezzo_totale = 80;
    $ordine->stato = 'elaborazione';
    $ordine->metodo_di_pagamento = '2134567891234567';

    $riguardaList = collect([]);

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->newInsert($ordine, $riguardaList))
        ->toThrow(\InvalidArgumentException::class, "La lista di riguarda è null o vuota");

    $expectedOrdini = require base_path('tests/resources/expected/Ordine.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testNewInsertONPRN', function () {

    $ordine = new Ordine();
    $ordine->codice = '15280754013';
    $ordine->data_acquisto = '2019-11-30';
    $ordine->indirizzo_di_consegna = 'viale croce';
    $ordine->cliente = 'a.maddaloni25@gmail.com';
    $ordine->prezzo_totale = 80;
    $ordine->stato = 'elaborazione';
    $ordine->metodo_di_pagamento = '2134567891234567';

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->newInsert($ordine, null))
        ->toThrow(\InvalidArgumentException::class, "La lista di riguarda è null o vuota");

    $expectedOrdini = require base_path('tests/resources/expected/Ordine.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testNewInsertONPRNV', function () {

    $ordine = new Ordine();
    $ordine->codice = '15280754013';
    $ordine->data_acquisto = '2019-11-30';
    $ordine->indirizzo_di_consegna = 'viale croce';
    $ordine->cliente = 'a.maddaloni25@gmail.com';
    $ordine->prezzo_totale = 80;
    $ordine->stato = 'elaborazione';
    $ordine->metodo_di_pagamento = '2134567891234567';

    $riguardaList = collect([
        ['prodotto' => 100, 'ordine' => 1000, 'quantita_acquistata' => 100]
    ]);

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->newInsert($ordine, $riguardaList))
        ->toThrow(\InvalidArgumentException::class, "La lista contiene elementi non validi");

    $expectedOrdini = require base_path('tests/resources/expected/Ordine.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});
// ----------------------NEW INSERT----------------------

// ----------------------DO UPDATE----------------------
test('testDoUpdateOPSNP', function () {

    // Inserisco l'ordine che andrò ad aggiornare
    DB::table('ordine')->insert([
        'codice' => '15280754013',
        'data_acquisto' => '2019-11-30',
        'indirizzo_di_consegna' => 'viale croce',
        'cliente' => 'a.maddaloni25@gmail.com',
        'prezzo_totale' => 80,
        'gestore' => null,
        'stato' => 'elaborazione',
        'metodo_di_pagamento' => '2134567891234567',
    ]);

    $ordine = Ordine::find('15280754013');

    $spedito = new Spedito();
    $spedito->corriere_espresso = 'Bartolini';
    $spedito->data_consegna = '2022-04-04';

    $ordineService = new OrdineService();
    $ordineService->doUpdate($ordine, $spedito);

    $expectedOrdini = require base_path('tests/resources/expected/OrdineDoUpdate.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }

    $expectedSpedito = require base_path('tests/resources/expected/SpeditoDoUpdate.php');
    $outputSpedito = Spedito::all();

    expect($outputSpedito)->toHaveCount(count($expectedSpedito));

    foreach ($expectedSpedito as $expectedRow) {
        $s = $outputSpedito->first(fn($item) => $item->ordine == $expectedRow['ordine'] && $item->corriere_espresso == $expectedRow['corriere_espresso']);
        expect($s)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $s->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testDoUpdateOPSP', function () {

    DB::table('ordine')->insert([
        'codice' => '15280754013',
        'data_acquisto' => '2019-11-30',
        'indirizzo_di_consegna' => 'viale croce',
        'cliente' => 'a.maddaloni25@gmail.com',
        'prezzo_totale' => 80,
        'gestore' => null,
        'stato' => 'elaborazione',
        'metodo_di_pagamento' => '2134567891234567',
    ]);

    // Inserisco manualmente una riga in spedito con chiave che andrà in conflitto
    DB::table('spedito')->insert([
        'ordine' => '15280754013',
        'corriere_espresso' => 'SDA',
        'data_consegna' => '2022-02-02',
    ]);

    $ordine = Ordine::find('15280754013');

    $spedito = new Spedito();
    $spedito->ordine = '15280754013';
    $spedito->corriere_espresso = 'SDA'; // già presente
    $spedito->data_consegna = '2022-02-02';

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->doUpdate($ordine, $spedito))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);
});

test('testDoUpdateOPSN', function () {

    DB::table('ordine')->insert([
        'codice' => '15280754013',
        'data_acquisto' => '2019-11-30',
        'indirizzo_di_consegna' => 'viale croce',
        'cliente' => 'a.maddaloni25@gmail.com',
        'prezzo_totale' => 80,
        'gestore' => null,
        'stato' => 'elaborazione',
        'metodo_di_pagamento' => '2134567891234567',
    ]);

    $ordine = Ordine::find('15280754013');

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->doUpdate($ordine, null))
        ->toThrow(\InvalidArgumentException::class, "Lo spedito è null");
});

test('testDoUpdateONPSNP', function () {

    $ordine = new Ordine();
    $ordine->codice = '15280754013';
    $ordine->data_acquisto = '2019-11-30';
    $ordine->indirizzo_di_consegna = 'viale croce';
    $ordine->cliente = 'a.maddaloni25@gmail.com';
    $ordine->prezzo_totale = 80;
    $ordine->stato = 'elaborazione';
    $ordine->metodo_di_pagamento = '2134567891234567';

    $spedito = new Spedito();
    $spedito->ordine = '100000000'; // non esiste nel DB
    $spedito->corriere_espresso = 'SDA';
    $spedito->data_consegna = '2022-02-02';

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->doUpdate($ordine, $spedito))
        ->toThrow(\Exception::class);

    $expectedOrdini = require base_path('tests/resources/expected/Ordine.php');
    $outputOrdini = Ordine::all();

    expect($outputOrdini)->toHaveCount(count($expectedOrdini));

    foreach ($expectedOrdini as $expectedRow) {
        $o = $outputOrdini->firstWhere('codice', $expectedRow['codice']);
        expect($o)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $o->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }

    $expectedSpedito = require base_path('tests/resources/expected/Spedito.php');
    $outputSpedito = Spedito::all();

    expect($outputSpedito)->toHaveCount(count($expectedSpedito));

    foreach ($expectedSpedito as $expectedRow) {
        $s = $outputSpedito->first(fn($item) => $item->ordine == $expectedRow['ordine'] && $item->corriere_espresso == $expectedRow['corriere_espresso']);
        expect($s)->not->toBeNull();
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $s->$campo;
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testDoUpdateONSNP', function () {

    $ordineService = new OrdineService();

    expect(fn() => $ordineService->doUpdate(null, new Spedito()))
        ->toThrow(\InvalidArgumentException::class, "L'ordine è null");
});
// ----------------------DO UPDATE----------------------