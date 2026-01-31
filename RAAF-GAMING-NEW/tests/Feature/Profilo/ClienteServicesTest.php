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

    expect($output)
    ->toBeInstanceOf(Cliente::class)
    ->and($output->email)->toBe("f.peluso25@gmail.com")
    ->and($output->nome)->toBe("Francesco")
    ->and($output->cognome)->toBe("Peluso")
    ->and($output->data_di_nascita)->toBe("2000-08-24")
    ->and($output->password)->toBe("veloce123")
    ->and($output->carta_fedelta)->toBe("1234567899")
    ->and($output->cartadicredito)->toBe("1234123412341235");
});
