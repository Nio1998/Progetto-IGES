<?php

use App\Models\Profilo\CartaDiCredito;
use App\Models\Profilo\CartaFedelta;
use App\Models\Profilo\Cliente;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\Profilo\CartaDiCreditoService;
use App\Services\Profilo\CartaFedeltaService;
use Database\Seeders\TestCartaFedeltaSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
use Database\Seeders\TestClienteSeeder;

uses()->group('CartaDiFedeltaUnit', 'Unit');

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
        $schemaCarta = base_path('tests/resources/init/cartafedelta.sql');
        $schemaCliente = base_path('tests/resources/init/cliente.sql'); 
        
        if (file_exists($schemaCarta)) {
            DB::unprepared(file_get_contents($schemaCarta));
        }
        
        // AGGIUNGI la tabella cliente
        if (file_exists($schemaCliente)) {
            DB::unprepared(file_get_contents($schemaCliente));
        }
        
        // Esegui i seeder
        $this->seed(TestCartaFedeltaSeeder::class);
        $this->seed(TestClienteSeeder::class);
        // Probabilmente serve anche un seeder per i clienti
        
        $dbInitialized = true;
    }
    
    DB::beginTransaction();
    Session::flush();
});

afterEach(function () {
    DB::rollback();
    Session::flush();
});

test('testRicercaPerChiavePresente', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    $output = $cartaFedeltaService->ricercaPerChiave('1234567897');

    // Verifica che il risultato non sia null
    expect($output)->not->toBeNull()
        // Verifica che sia un'istanza di CartaFedeltà
        ->and($output)->toBeInstanceOf(CartaFedelta::class)
        // Verifica che il codice corrisponda
        ->and($output->codice)->toBe('1234567897')
        ->and($output->punti)->toBe(0);
});

test('testRicercaPerChiaveNonPresente', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    $output = $cartaFedeltaService->ricercaPerChiave('1234567896');

    // Verifica che il risultato non sia null
    expect($output)->toBeNull();
        // Verifica che sia un'istanza di CartaFedeltà
});

test('testRicercaPerChiaveVuoto', function () {
    $cartaFedeltaService = new CartaFedeltaService();

        expect(fn() => $cartaFedeltaService->ricercaPerChiave(""))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testRicercaPerChiaveNull', function () {
    $cartaFedeltaService = new CartaFedeltaService();

        expect(fn() => $cartaFedeltaService->ricercaPerChiave(null))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testAllElementsCrescente', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    $ordinamento = "codice asc";
    $output = $cartaFedeltaService->allElements($ordinamento);
    $output->toArray();
    $expected = require base_path('tests/resources/expected/CartaFedeltaAsc.php');
    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $output[$index]->$campo;
            // Se è oggetto Carbon, lo converto in stringa Y-m-d (questo per le date)
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testAllElementsDecrescente', function () {
    $cartaFedeltaService = new CartaFedeltaService();

    $carte = $cartaFedeltaService->allElements('codice desc');

    expect($carte)->toBeInstanceOf(Collection::class)
        // Verifica che non sia vuota (assumendo che ci siano dati dal seeder)
        ->and($carte->count())->toBeGreaterThan(0);

    // Verifica che sia ordinata in modo DECRESCENTE per codice
    $codici = $carte->pluck('codice')->toArray();
    $codiciOrdinati = $codici;
    rsort($codiciOrdinati); // Usa rsort per ordinamento decrescente
    
    expect($codici)->toBe($codiciOrdinati);
});

test('testAllElementsOrdinamentoNonValido', function () {
    $cartaFedeltaService = new CartaFedeltaService();

    expect(fn() => $cartaFedeltaService->allElements('Peppe dasc'))
        ->toThrow(\InvalidArgumentException::class, "ordinamento scritto in modo errato");
});

test('testAllElementVuoto', function () {
    $cartaFedeltaService = new CartaFedeltaService();

    expect(fn() => $cartaFedeltaService->allElements(''))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});

test('testAllElementNull', function () {
    $cartaFedeltaService = new CartaFedeltaService();

    expect(fn() => $cartaFedeltaService->allElements(null))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});

test('testnewInsertCFNDB', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    
    $cartaf = new CartaFedelta();
    $cartaf->codice = "9999999999";
    $cartaf->punti = 50;
    
    $cartaFedeltaService->newInsert($cartaf);
    
    $expected = require base_path('tests/resources/expected/CartaFedeltaNewInsert.php');
    $actual = DB::table('cartafedelta')->get()->toArray();
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }
    
    expect($cartaf)->not->toBeNull()
    ->and($cartaf->codice)->toBe('9999999999')
    ->and($cartaf->punti)->toBe(50);
});

test('testNewInsertCFPDB', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    
    $cartaf = new CartaFedelta();
    $cartaf->codice = "1234567897"; // Codice già esistente
    $cartaf->punti = 1;
    
    // Verifica che lanci l'eccezione di violazione UNIQUE constraint
    expect(fn() => $cartaFedeltaService->newInsert($cartaf))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);
});

test('testNewInsertCFNULL', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    
    $cartaf = null;

    expect(fn() => $cartaFedeltaService->newInsert($cartaf))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item null");
});

test('testDoUpdateNonPresenteNocliente', function () {
    $cartaFedeltaService = new CartaFedeltaService();
    
    // Assicurati che NON ci sia un cliente in sessione
    Session::forget('Cliente');
    expect(Session::has('Cliente'))->toBeFalse();
    
    $cartaf = new CartaFedelta();
    $cartaf->codice = "9999999999"; 
    $cartaf->punti = 100;
    
    // Tenta di aggiornare una carta che NON esiste nel DB
    $codiceNonEsistente = "1234567896";
    $cartaFedeltaService->doUpdate($cartaf, $codiceNonEsistente);
    
    // Verifica che la carta NON sia stata creata con il codice non esistente
    $cartaNonCreata = DB::table('cartafedelta')->where('codice', $codiceNonEsistente)->first();
    expect($cartaNonCreata)->toBeNull();
    
    // Verifica che NON sia stata creata nemmeno con il nuovo codice
    $cartaNuovaNonCreata = DB::table('cartafedelta')->where('codice', '9999999999')->first();
    expect($cartaNuovaNonCreata)->toBeNull();
    
    // Verifica che il database contenga solo le carte originali dal seeder
    $expected = require base_path('tests/resources/expected/CartaFedeltaDoUpdateNoPresente.php'); // O un file specifico per questo test
    $actual = DB::table('cartafedelta')->get()->toArray();
    
    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $actual[$index]->$campo;
            
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            
            expect($actualValue)->toBe($valore);
        }
    }
    
    // Verifica che il Cliente NON sia in sessione
    expect(Session::has('Cliente'))->toBeFalse();

});

test('testDoUpdatePresente', function() {

    $cartaFedeltaService = new CartaFedeltaService();
    
    // Setup del Cliente in sessione
    $cliente = new Cliente();
    $cliente->email = "b.veluso25@gmail.com";
    $cliente->nome = "Brancesco";
    $cliente->cognome = "Veluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "fdae384f2bd81b9d7ccb92acd17dd4d5";
    $cliente->carta_fedelta = "1234567898";
    $cliente->cartadicredito = "1234123412341237";

    // Setup della Carta da aggiornare
    $cartaf = CartaFedelta::where('codice', '1234567898')->first();

    $cliente->setRelation('cartafedelta', $cartaf);

    Session::put('Cliente', $cliente);
    // Esecuzione del metodo doUpdate
    $cartaFedeltaService->doUpdate($cartaf);

    // Recupero dati attuali dal DB
    $actual = DB::table('cartafedelta')->get()->toArray();

    // Recupero dati attesi dal file di resource
    $expected = require base_path('tests/resources/expected/CartaFedeltaDoUpdatePresente.php');
    
    // Verifica numero di record
    expect($actual)->toHaveCount(count($expected));
    
    // Verifica campo per campo (identico al tuo esempio)
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $actual[$index]->$campo;
            
            if ($actualValue instanceof \Carbon\Carbon) {
                $actualValue = $actualValue->format('Y-m-d');
            }
            
            expect($actualValue)->toBe($valore);
        }
    }

    // Verifica della Sessione (per assicurarsi che il cliente sia ancora loggato/corretto)
    $outputSessione = Session::get('Cliente');
    
    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("b.veluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Brancesco")
        ->and($outputSessione->cognome)->toBe("Veluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("fdae384f2bd81b9d7ccb92acd17dd4d5")
        ->and($outputSessione->carta_fedelta)->toBe("1234567898")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341237");
});

test('testDoUpdateNull', function () {
    $cartaFedeltaService = new CartaFedeltaService();

        expect(fn() => $cartaFedeltaService->doUpdate(null))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});
