<?php

use App\Models\Profilo\Cliente;
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
        $schemaCliente = base_path('tests/resources/init/cliente.sql');
        
        if (file_exists($schemaCliente)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaCliente));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaCliente}");
        }
        
        // Esegui SOLO i seeder che ti servono
        $this->seed(TestClienteSeeder::class);
        
        $dbInitialized = true;
    }
    
    // Inizia transazione per ogni test
    DB::beginTransaction();
});

afterEach(function () {
    DB::rollback();
});


test('testRicercaPerChiavePresenteDBnoST', function () {

    $clienti = Cliente::all();
    expect($clienti)->toHaveCount(2);

});
