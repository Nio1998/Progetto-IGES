<?php

use App\Models\Profilo\CartaDiCredito;
use App\Models\Profilo\Cliente;
use Database\Seeders\TestCartaDiCreditoSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\Profilo\CartaDiCreditoService;
use Illuminate\Support\Facades\Session;
use Database\Seeders\TestClienteSeeder;

uses()->group('CartaDiCreditoUnit', 'Unit');

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
        $schemaCarta = base_path('tests/resources/init/cartadicredito.sql');
        $schemaCliente = base_path('tests/resources/init/cliente.sql'); 
        
        if (file_exists($schemaCarta)) {
            DB::unprepared(file_get_contents($schemaCarta));
        }
        
        // AGGIUNGI la tabella cliente
        if (file_exists($schemaCliente)) {
            DB::unprepared(file_get_contents($schemaCliente));
        }
        
        // Esegui i seeder
        $this->seed(TestCartaDiCreditoSeeder::class);
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

test('testRicercaPerChiavePresenteDB', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    $output = $cartaDiCreditoService->ricercaPerChiave('1234123412341235');

    // Verifica che il risultato non sia null
    expect($output)->not->toBeNull()
        // Verifica che sia un'istanza di CartaDiCredito
        ->and($output)->toBeInstanceOf(CartaDiCredito::class)
        // Verifica che il codice corrisponda
        ->and($output->codicecarta)->toBe('1234123412341235')
        ->and($output->data_scadenza->format('Y-m-d'))->toBe("2028-12-12")
        ->and($output->codice_cvv)->toBe(012);
});

test('testRicercaPerChiaveNonPresenteDB', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    $output = $cartaDiCreditoService->ricercaPerChiave('0');

    // Verifica che il risultato sia null
    expect($output)->toBeNull();

});

test('testRicercaPerChiaveVuota', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    expect(fn() => $cartaDiCreditoService->ricercaPerChiave(''))
         ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testRicercaPerChiaveNull', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    expect(fn() => $cartaDiCreditoService->ricercaPerChiave(null))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testNewInsertSuccess', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    $newCarta = new CartaDiCredito();
    $newCarta->codicecarta = '1234123412341234';
    $newCarta->data_scadenza = '2028-12-12';
    $newCarta->codice_cvv = 012;

    $cartaDiCreditoService->newInsert($newCarta);

    $expected = require base_path('tests/resources/expected/CartaDiCreditoNewInsert.php');
    $actual = DB::table('cartadicredito')->get()->toArray();

    expect($actual)->toHaveCount(count($expected));

    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {

            if ($campo === 'data_scadenza') {
                expect(
                    \Carbon\Carbon::parse($actual[$index]->$campo)->toDateString()
                )->toBe($valore);
            } else {
                expect($actual[$index]->$campo)->toBe($valore);
            }
        }
    }
});


test('testNewInsertGiaPresente', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    $newCarta = new CartaDiCredito();
    $newCarta->codicecarta = '1234123412341235'; // Codice già nel DB
    $newCarta->data_scadenza = '2028-12-12';
    $newCarta->codice_cvv = '012';
    
    // Verifica che lanci l'eccezione di violazione UNIQUE constraint
    expect(fn() => $cartaDiCreditoService->newInsert($newCarta))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);
});

test('testDoUpdateCCPDBCODNULL', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();

    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "05ec5bed5c2756b6b305b7fcd7e4b6df";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341235";
    
    Session::put('Cliente',$cliente);
    
    // Prepara i dati della carta da aggiornare
    $cartaDaAggiornare = new CartaDiCredito();
    $cartaDaAggiornare->codicecarta = '1234123412341234';
    $cartaDaAggiornare->data_scadenza = '2028-12-12';
    $cartaDaAggiornare->codice_cvv = 012;

    $codiceNonEsistente = null;
    
    expect(fn() => $cartaDiCreditoService->doUpdate($cartaDaAggiornare, $codiceNonEsistente))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item null o codice null");

    $actual = DB::table('cartadicredito')->get()->toArray();

    $expected = require base_path('tests/resources/expected/CartaDiCreditoDoUpdateNonPresente.php');

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
    
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("05ec5bed5c2756b6b305b7fcd7e4b6df")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341235");
});

test('testDoUpdateCCPDBCODNO', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();

    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "05ec5bed5c2756b6b305b7fcd7e4b6df";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341235";
    
    Session::put('Cliente',$cliente);
    
    // Prepara i dati della carta da aggiornare
    $cartaDaAggiornare = new CartaDiCredito();
    $cartaDaAggiornare->codicecarta = '1234123412341234';
    $cartaDaAggiornare->data_scadenza = '2028-12-12';
    $cartaDaAggiornare->codice_cvv = '012';

    $codiceNonEsistente = "1234123412341236";
    
    $cartaDiCreditoService->doUpdate($cartaDaAggiornare, $codiceNonEsistente);

    $actual = DB::table('cartadicredito')->get()->toArray();

    $expected = require base_path('tests/resources/expected/CartaDiCreditoDoUpdateNonPresente.php');

    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $actual[$index]->$campo;            
            expect($actualValue)->toBe($valore);
        }
    }
    
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("05ec5bed5c2756b6b305b7fcd7e4b6df")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341235");
});

test('testDoUpdateCCNDBCODSI', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();

    $cliente = new Cliente();
    $cliente->email = "f.peluso26@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "05ec5bed5c2756b6b305b7fcd7e4b6df";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341239";
    
    Session::put('Cliente',$cliente);
    
    // Prepara i dati della carta da aggiornare
    $cartaDaAggiornare = new CartaDiCredito();
    $cartaDaAggiornare->codicecarta = '1234123412341234';
    $cartaDaAggiornare->data_scadenza = '2028-12-12';
    $cartaDaAggiornare->codice_cvv = 012;

    $codiceNonEsistente = "1234123412341235";
    
    $cartaDiCreditoService->doUpdate($cartaDaAggiornare, $codiceNonEsistente);

    $actual = DB::table('cartadicredito')->get()->toArray();

    $expected = require base_path('tests/resources/expected/CartaDiCreditoDoUpdatePresente.php');

    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $actual[$index]->$campo;            
            expect($actualValue)->toBe($valore);
        }
    }
    
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso26@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("05ec5bed5c2756b6b305b7fcd7e4b6df")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341239");
});

test('testDoUpdateCCPDBCODSI', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();

    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "05ec5bed5c2756b6b305b7fcd7e4b6df";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341235";

    // Prepara i dati della carta da aggiornare
    $cartaDaAggiornare = new CartaDiCredito();
    $cartaDaAggiornare->codicecarta = '1234123412341234';
    $cartaDaAggiornare->data_scadenza = '2028-12-12';
    $cartaDaAggiornare->codice_cvv = 012;
    $cliente->setRelation('cartacredito', $cartaDaAggiornare);
    Session::put('Cliente',$cliente);

    $codiceNonEsistente = "1234123412341235";

    $cartaDiCreditoService->doUpdate($cartaDaAggiornare, $codiceNonEsistente);

    $actual = DB::table('cartadicredito')->get()->toArray();

    $expected = require base_path('tests/resources/expected/CartaDiCreditoDoUpdatePresente.php');

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
    
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("05ec5bed5c2756b6b305b7fcd7e4b6df")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341234");
});

test('testDoUpdateCCNULLCODSI', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();

    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "05ec5bed5c2756b6b305b7fcd7e4b6df";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341235";
    
    Session::put('Cliente',$cliente);
    
    // Prepara i dati della carta da aggiornare
    $cartaDaAggiornare = null;

    $codiceNonEsistente = "1234123412341235";

    expect(fn() => $cartaDiCreditoService->doUpdate($cartaDaAggiornare, $codiceNonEsistente))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item null o codice null");

    $actual = DB::table('cartadicredito')->get()->toArray();

    $expected = require base_path('tests/resources/expected/CartaDiCreditoDoUpdateNonPresente.php');

    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $actual[$index]->$campo;            
            expect($actualValue)->toBe($valore);
        }
    }
    
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("05ec5bed5c2756b6b305b7fcd7e4b6df")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341235");
});