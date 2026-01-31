<?php

use App\Models\Profilo\Cliente;
use Illuminate\Support\Facades\Session;
use App\Services\Profilo\ClienteService;
use Database\Seeders\TestCartaDiCreditoSeeder;
use Database\Seeders\TestCartaFedeltaSeeder;
use Database\Seeders\TestClienteSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses()->group('ClienteUnit', 'Unit');

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
        $schemaCartaC = base_path('tests/resources/init/cartadicredito.sql');
        $schemaCartaF = base_path('tests/resources/init/cartafedelta.sql');
        $schemaCliente = base_path('tests/resources/init/cliente.sql');
        
        if (file_exists($schemaCliente)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaCartaC));
            DB::unprepared(file_get_contents($schemaCartaF));
            DB::unprepared(file_get_contents($schemaCliente));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaCliente}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestCartaDiCreditoSeeder::class);
        $this->seed(TestCartaFedeltaSeeder::class);
        $this->seed(TestClienteSeeder::class);
        
        $dbInitialized = true;
    }
    
    // Inizia transazione per ogni test
    DB::beginTransaction();
    Session::flush();
});

afterEach(function () {
    DB::rollback();
    Session::flush();
});


test('testRicercaPerChiavePresenteDBnoST', function () {

    $clienteService = new ClienteService();
    $output = $clienteService->ricercaPerChiave("f.peluso25@gmail.com",true);
    $outputSessione = Session::get('Cliente');

    expect($output)
    ->toBeInstanceOf(Cliente::class)
    ->and($output->email)->toBe("f.peluso25@gmail.com")
    ->and($output->nome)->toBe("Francesco")
    ->and($output->cognome)->toBe("Peluso")
    ->and($output->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
    ->and($output->password)->toBe("veloce123")
    ->and($output->carta_fedelta)->toBe("1234567897")
    ->and($output->cartadicredito)->toBe("1234123412341235");

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("veloce123")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341235");
});

test('testRicercaPerChiavePresenteDBnoSF', function () {

    $clienteService = new ClienteService();
    $output = $clienteService->ricercaPerChiave("f.peluso25@gmail.com",false);
    $outputSessione = Session::get('Cliente');

    expect($output)
    ->toBeInstanceOf(Cliente::class)
    ->and($output->email)->toBe("f.peluso25@gmail.com")
    ->and($output->nome)->toBe("Francesco")
    ->and($output->cognome)->toBe("Peluso")
    ->and($output->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
    ->and($output->password)->toBe("veloce123")
    ->and($output->carta_fedelta)->toBe("1234567897")
    ->and($output->cartadicredito)->toBe("1234123412341235");

    expect($outputSessione)->toBeNull();
});

test('testRicercaPerChiaveNonPresente', function () {

    $clienteService = new ClienteService();
    $output = $clienteService->ricercaPerChiave("abcdefghi",true);
    $outputSessione = Session::get('Cliente');

    expect($output)->toBeNull();

    expect($outputSessione)->toBeNull();
});

test('testRicercaPerChiaveNull', function () {
    $clienteService = new ClienteService();
    expect(fn() => $clienteService->ricercaPerChiave(null, true))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testRicercaPerChiaveEmpty', function () {
    $clienteService = new ClienteService();
    expect(fn() => $clienteService->ricercaPerChiave("", true))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");
});

test('testRicercaPerChiavePresenteDBsiST', function () {
    // Da questo si capisce c'è l'utente con la password veloce123 mentre capiamo che invece si tratta di quello in sessione
    // perche la password è diversa, rispetto all'oggetto in sessione
    // quindi l'utente sul DB non lo tocchiamo prorpio
    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "veloce1234";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341235";

    Session::put('Cliente',$cliente);

    $output = $clienteService->ricercaPerChiave("f.peluso25@gmail.com",true);
    $outputSessione = Session::get('Cliente');

    expect($output)
    ->toBeInstanceOf(Cliente::class)
    ->and($output->email)->toBe("f.peluso25@gmail.com")
    ->and($output->nome)->toBe("Francesco")
    ->and($output->cognome)->toBe("Peluso")
    ->and($output->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
    ->and($output->password)->toBe("veloce1234")
    ->and($output->carta_fedelta)->toBe("1234567897")
    ->and($output->cartadicredito)->toBe("1234123412341235");

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("veloce1234")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341235");
});

test('testRicercaPerChiavePresenteSnoDBT', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "alfio@gmail.com";
    $cliente->nome = "Alfio";
    $cliente->cognome = "Alfio";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "veloce1234";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341235";

    Session::put('Cliente',$cliente);

    $output = $clienteService->getUtenteAutenticato();
    $outputSessione = Session::get('Cliente');

    expect($output)
    ->toBeInstanceOf(Cliente::class)
    ->and($output->email)->toBe("alfio@gmail.com")
    ->and($output->nome)->toBe("Alfio")
    ->and($output->cognome)->toBe("Alfio")
    ->and($output->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
    ->and($output->password)->toBe("veloce1234")
    ->and($output->carta_fedelta)->toBe("1234567897")
    ->and($output->cartadicredito)->toBe("1234123412341235");

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("alfio@gmail.com")
        ->and($outputSessione->nome)->toBe("Alfio")
        ->and($outputSessione->cognome)->toBe("Alfio")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("veloce1234")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341235");
});

test('testGetUtenteAutenticatoCV', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "veloce123";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341235";

    Session::put('Cliente',$cliente);

    $output = $clienteService->getUtenteAutenticato();
    $outputSessione = Session::get('Cliente');

    expect($output)
    ->toBeInstanceOf(Cliente::class)
    ->and($output->email)->toBe("f.peluso25@gmail.com")
    ->and($output->nome)->toBe("Francesco")
    ->and($output->cognome)->toBe("Peluso")
    ->and($output->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
    ->and($output->password)->toBe("veloce123")
    ->and($output->carta_fedelta)->toBe("1234567897")
    ->and($output->cartadicredito)->toBe("1234123412341235");

    expect($outputSessione)->not->toBeNull()
        ->and($outputSessione->email)->toBe("f.peluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Francesco")
        ->and($outputSessione->cognome)->toBe("Peluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("veloce123")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341235");
});

test('testGetUtenteAutenticatoCVN', function () {

    $clienteService = new ClienteService();
    Session::put('Cliente',null);
    $output = $clienteService->getUtenteAutenticato();
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->toBeNull();

    expect($outputSessione)->toBeNull();
});

test('testGetUtenteAutenticatoCN', function () {

    $clienteService = new ClienteService();
    $output = $clienteService->getUtenteAutenticato();
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->toBeNull();

    expect($outputSessione)->toBeNull();
});

test('testGetUtenteAutenticatoCVNV', function () {

    $clienteService = new ClienteService();
    Session::put('Cliente','nonvalido');
    

    expect(fn() => $clienteService->getUtenteAutenticato())
        ->toThrow(\TypeError::class);

    $outputSessione = Session::get('Cliente');
    
    expect($outputSessione)->toBe('nonvalido');
});
