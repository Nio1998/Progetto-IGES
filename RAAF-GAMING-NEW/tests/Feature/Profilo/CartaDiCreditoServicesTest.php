<?php

use App\Models\Profilo\CartaDiCredito;
use App\Models\Profilo\Cliente;
use Database\Seeders\TestCartaDiCreditoSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Services\Profilo\CartaDiCreditoService;
use Illuminate\Support\Facades\Session;

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

    // Verifica che la carta sia effettivamente nel database
    $fetchedCarta = $cartaDiCreditoService->ricercaPerChiave('1234123412341234');

    expect($fetchedCarta)->not->toBeNull()
        ->and($fetchedCarta)->toBeInstanceOf(CartaDiCredito::class)
        ->and($fetchedCarta->codicecarta)->toBe('1234123412341234')
        ->and($fetchedCarta->data_scadenza->format('Y-m-d'))->toBe("2028-12-12")
        ->and($fetchedCarta->codice_cvv)->toBe(012);
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

test('testDoUpdateNonPresenteCliente', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    // Assicurati che NON ci sia un cliente in sessione
    Session::forget('Cliente');
    expect(Session::has('Cliente'))->toBeFalse();
    
    // Prepara i dati di una carta
    $cartaDaAggiornare = new CartaDiCredito();
    $cartaDaAggiornare->codicecarta = '1234123412341234';
    $cartaDaAggiornare->data_scadenza = '2028-12-12';
    $cartaDaAggiornare->codice_cvv = '012';
    
    // Tenta di aggiornare una carta che NON esiste nel DB
    $codiceNonEsistente = '1234123412341239';
    
    // Esegui l'update - non deve lanciare eccezioni
    $cartaDiCreditoService->doUpdate($cartaDaAggiornare, $codiceNonEsistente);
    
    // Verifica che la carta NON sia stata creata nel database
    $fetchedCarta = $cartaDiCreditoService->ricercaPerChiave($codiceNonEsistente);
    expect($fetchedCarta)->toBeNull();
    
    // Verifica che NON sia stata creata nemmeno con il nuovo codice
    $fetchedCartaNuova = $cartaDiCreditoService->ricercaPerChiave('1234123341234');
    expect($fetchedCartaNuova)->toBeNull();
    
    // Verifica che il Cliente NON sia in sessione
    expect(Session::has('Cliente'))->toBeFalse();
});

test('testDoUpdatePresente', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    // Crea un cliente di test con tutti i campi obbligatori
    $cliente = new Cliente();
    $cliente->email = 'test@example.com';
    $cliente->nome = 'Mario';
    $cliente->cognome = 'Rossi';
    $cliente->data_di_nascita = '1990-01-01'; 
    $cliente->password = 'password123';
    $cliente->carta_fedelta = 'CF1234567890';
    $cliente->cartadicredito = '1234123412341235'; 
    $cliente->save();

    // Carica la relazione cartacredito sul cliente
    $cliente->load('cartacredito');
    
    // Metti il cliente in sessione DOPO aver caricato la relazione
    Session::put('Cliente', $cliente);
   
    $newCarta = new CartaDiCredito();
    $newCarta->codicecarta = '9999888877776666';
    $newCarta->data_scadenza = '2030-11-11';
    $newCarta->codice_cvv = 999;
    
    // Esegui l'update
    $cartaDiCreditoService->doUpdate($newCarta, '1234123412341235');

    // Verifica che la carta sia stata aggiornata nel database
    $cartaAggiornata = $cartaDiCreditoService->ricercaPerChiave('9999888877776666');
    expect($cartaAggiornata)->not->toBeNull()
        ->and($cartaAggiornata)->toBeInstanceOf(CartaDiCredito::class)
        ->and($cartaAggiornata->codicecarta)->toBe('9999888877776666')
        ->and($cartaAggiornata->data_scadenza->format('Y-m-d'))->toBe('2030-11-11')
        ->and($cartaAggiornata->codice_cvv)->toBe(999);
    
    // Verifica che la vecchia carta non esista più con il vecchio codice
    $cartaVecchia = $cartaDiCreditoService->ricercaPerChiave('1234123412341235');
    expect($cartaVecchia)->toBeNull();
    
    // Verifica che l'istanza Cliente nella sessione sia stata aggiornata
    $clienteSessione = Session::get('Cliente');
    expect($clienteSessione)->not->toBeNull()
        ->and($clienteSessione->cartadicredito)->toBe('9999888877776666')
        ->and($clienteSessione->cartacredito)->not->toBeNull()
        ->and($clienteSessione->cartacredito->codicecarta)->toBe('9999888877776666')
        ->and($clienteSessione->cartacredito->data_scadenza->format('Y-m-d'))->toBe('2030-11-11')
        ->and($clienteSessione->cartacredito->codice_cvv)->toBe(999);
});

test('testDoUpdateNull', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    // Verifica che venga lanciata un'eccezione passando item null
    expect(fn() => $cartaDiCreditoService->doUpdate(null, '1234123412341235'))
        ->toThrow(\InvalidArgumentException::class); 
    
});

test('testDoUpdateNoPresenteSiCliente', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();

    $cliente = new Cliente();
    $cliente->email = 'test@example.com';
    $cliente->nome = 'Mario';
    $cliente->cognome = 'Rossi';
    $cliente->data_di_nascita = '1990-01-01'; 
    $cliente->password = 'password123';
    $cliente->carta_fedelta = 'CF1234567890';
    $cliente->cartadicredito = '1234123412341235'; 
    $cliente->save();

    // Carica la relazione cartacredito sul cliente
    $cliente->load('cartacredito');
    
    // Metti il cliente in sessione DOPO aver caricato la relazione
    Session::put('Cliente', $cliente);

    $cartaDaAggiornare = new CartaDiCredito();
    $cartaDaAggiornare->codicecarta = '1234123412341234';
    $cartaDaAggiornare->data_scadenza = '2028-12-12';
    $cartaDaAggiornare->codice_cvv = 012;
    
    // Tenta di aggiornare una carta che NON esiste nel DB
    $codiceNonEsistente = '1234123412341299';
    $cartaDiCreditoService->doUpdate($cartaDaAggiornare, $codiceNonEsistente);
    
    // Verifica che la carta con il codice non esistente NON sia stata creata
    $fetchedCarta = $cartaDiCreditoService->ricercaPerChiave($codiceNonEsistente);
    expect($fetchedCarta)->toBeNull();
    
    // Verifica che NON sia stata creata nemmeno con il nuovo codice
    $fetchedCartaNuova = $cartaDiCreditoService->ricercaPerChiave('1234123412341234');
    expect($fetchedCartaNuova)->toBeNull();
    
    // Verifica che il Cliente in sessione sia rimasto invariato
    $clienteSessione = Session::get('Cliente');
    expect($clienteSessione)->not->toBeNull()
        ->and($clienteSessione->email)->toBe('test@example.com')
        ->and($clienteSessione->cartadicredito)->toBe('1234123412341235') // Deve rimanere il codice originale
        ->and($clienteSessione->cartacredito)->not->toBeNull()
        ->and($clienteSessione->cartacredito->codicecarta)->toBe('1234123412341235'); // La relazione non deve essere cambiata
});

test('testDoUpdatePresenteNoCliente', function () {
    $cartaDiCreditoService = new CartaDiCreditoService();
    
    // Crea un cliente collegato alla carta esistente dal seeder
    $cliente = new Cliente();
    $cliente->email = 'cliente@example.com';
    $cliente->nome = 'Giovanni';
    $cliente->cognome = 'Bianchi';
    $cliente->data_di_nascita = '1985-05-15'; 
    $cliente->password = 'password456';
    $cliente->carta_fedelta = 'CF0987654321';
    $cliente->cartadicredito = '1234123412341235'; // Collega alla carta esistente
    $cliente->save();
    
    // Assicurati che NON ci sia un cliente in sessione
    Session::forget('Cliente');
    expect(Session::has('Cliente'))->toBeFalse();
    
    // Prepara i dati aggiornati per una carta esistente
    $newCarta = new CartaDiCredito();
    $newCarta->codicecarta = '9999888877776666';
    $newCarta->data_scadenza = '2030-11-11';
    $newCarta->codice_cvv = 999;
    
    // Aggiorna una carta che ESISTE nel DB (dal seeder)
    $codiceEsistente = '1234123412341235';
    $cartaDiCreditoService->doUpdate($newCarta, $codiceEsistente);
    
    // Verifica che la carta sia stata aggiornata nel database
    $cartaAggiornata = $cartaDiCreditoService->ricercaPerChiave('9999888877776666');
    expect($cartaAggiornata)->not->toBeNull()
        ->and($cartaAggiornata)->toBeInstanceOf(CartaDiCredito::class)
        ->and($cartaAggiornata->codicecarta)->toBe('9999888877776666')
        ->and($cartaAggiornata->data_scadenza->format('Y-m-d'))->toBe('2030-11-11')
        ->and($cartaAggiornata->codice_cvv)->toBe(999);
    
    // Verifica che la vecchia carta non esista più
    $cartaVecchia = $cartaDiCreditoService->ricercaPerChiave($codiceEsistente);
    expect($cartaVecchia)->toBeNull();
    
    // Verifica che il Cliente NON sia ancora in sessione
    expect(Session::has('Cliente'))->toBeFalse();
});