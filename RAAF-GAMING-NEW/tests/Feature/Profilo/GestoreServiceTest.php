<?php

use App\Models\Profilo\Gestore;
use App\Services\Profilo\GestoreService;
use Database\Seeders\TestGestoreSeeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


uses()->group('GestoreUnit', 'Unit');

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
        $schemaGestore = base_path('tests/resources/init/gestore.sql');
        
        if (file_exists($schemaGestore)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaGestore));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaGestore}");
        }
        
        // Esegui SOLO i seeder che ti servono
        $this->seed(TestGestoreSeeder::class);
        
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

test('testricercaPerChiavePresenteDBCT', function () {
    $gestoreservice = new GestoreService();
    $output = $gestoreservice->ricercaPerChiave("gestore@hotmail.it",true);
    $outputsessione = Session::get('Gestore');

    expect($output)
    ->toBeInstanceOf(Gestore::class)
    ->and($output->email)->toBe("gestore@hotmail.it")
    ->and($output->ruolo)->toBe("ordine")
    ->and($output->password)->toBe("123456789");

    expect($outputsessione)->not->toBeNull()
    ->and($outputsessione->email)->toBe("gestore@hotmail.it")
    ->and($outputsessione->ruolo)->toBe("ordine")
    ->and($outputsessione->password)->toBe("123456789");
});


test('testricercaPerChiavePresenteDBCF', function () {
    $gestoreservice = new GestoreService();
    $output = $gestoreservice->ricercaPerChiave("gestore@hotmail.it",false);


    expect($output)
    ->toBeInstanceOf(Gestore::class)
    ->and($output->email)->toBe("gestore@hotmail.it")
    ->and($output->ruolo)->toBe("ordine")
    ->and($output->password)->toBe("123456789");

});

test('testricercaPerChiaveNonPresenteDBCT', function () {
    $gestoreservice = new GestoreService();
    $output = $gestoreservice->ricercaPerChiave("mario@hotmail.it", true);
    $outputsessione = Session::get('Gestore');

    // 1. L'output deve essere null (perché il gestore non esiste)
    expect($output)->toBeNull();

    // 2. Anche la sessione deve essere vuota (o non contenere quel gestore)
    expect($outputsessione)->toBeNull();

});

test('testricercaPerChiaveNullCT', function () {
    $gestoreservice = new GestoreService();
    $outputsessione = Session::get('Gestore');

    expect(fn() => $gestoreservice->ricercaPerChiave(null, true))
        ->toThrow(\InvalidArgumentException::class, "Inserito un id null o vuoto");

    // 2. Anche la sessione deve essere vuota (o non contenere quel gestore)
    expect($outputsessione)->toBeNull();

});

test('testricercaPerChiaveVoidCT', function () {
    $gestoreservice = new GestoreService();

    // 1. Verifichiamo che venga lanciata l'eccezione
    // L'oracolo dice: "Viene generato un errore"
    expect(fn() => $gestoreservice->ricercaPerChiave("", true))
        ->toThrow(\Exception::class); 

    // 2. Verifichiamo che la sessione sia rimasta vuota
    $outputsessione = Session::get('Gestore');
    expect($outputsessione)->toBeNull();
});

test('testricercaPerChiavePresenteDBSISCT', function () {
    $gestoreservice = new GestoreService();
    $gestore = new Gestore();
    $gestore->email = "gestore1@hotmail.com";
    $gestore->ruolo = "ordine";
    $gestore->password = "123456789";

    Session::put('Gestore', $gestore);

    $output = $gestoreservice->ricercaPerChiave("gestore1@hotmail.com",true);
    $outputsessione = Session::get('Gestore');

    expect($output)
    ->toBeInstanceOf(Gestore::class)
    ->and($output->email)->toBe("gestore1@hotmail.com")
    ->and($output->ruolo)->toBe("ordine")
    ->and($output->password)->toBe("123456789");

    // Verifichiamo che la sessione contenga ancora i dati corretti
    expect($outputsessione)->not->toBeNull()
    ->and($outputsessione->email)->toBe("gestore1@hotmail.com")
    ->and($outputsessione->ruolo)->toBe("ordine")
    ->and($outputsessione->password)->toBe("123456789");
});

test('testricercaPerChiaveNonPresenteDBSINSCT', function () {
    $gestoreservice = new GestoreService();
    $gestore = new Gestore();
    $gestore->email = "gestore2@hotmail.com";
    $gestore->ruolo = "ordine";
    $gestore->password = "123456789";

    Session::put('Gestore', $gestore);

    $output = $gestoreservice->ricercaPerChiave("gestore2@hotmail.com",false);
    $outputsessione = Session::get('Gestore');

    expect($output)
    ->toBeInstanceOf(Gestore::class)
    ->and($output->email)->toBe("gestore2@hotmail.com")
    ->and($output->ruolo)->toBe("ordine")
    ->and($output->password)->toBe("123456789");

    // Verifichiamo che la sessione contenga ancora i dati corretti
    expect($outputsessione)->not->toBeNull()
    ->and($outputsessione->email)->toBe("gestore2@hotmail.com")
    ->and($outputsessione->ruolo)->toBe("ordine")
    ->and($outputsessione->password)->toBe("123456789");
});


test('testgetUtenteAutenticatoGV', function () {
    $gestoreservice = new GestoreService();
    $gestore = new Gestore();
    $gestore->email = "gestore1@hotmail.com";
    $gestore->ruolo = "ordine";
    $gestore->password = "123456789";

    Session::put('Gestore', $gestore);

    $output = $gestoreservice->getUtenteAutenticato();
    $outputsessione = Session::get('Gestore');

    expect($output)
    ->toBeInstanceOf(Gestore::class)
    ->and($output->email)->toBe("gestore1@hotmail.com")
    ->and($output->ruolo)->toBe("ordine")
    ->and($output->password)->toBe("123456789");

    // Verifichiamo che la sessione contenga ancora i dati corretti
    expect($outputsessione)->not->toBeNull()
    ->and($outputsessione->email)->toBe("gestore1@hotmail.com")
    ->and($outputsessione->ruolo)->toBe("ordine")
    ->and($outputsessione->password)->toBe("123456789");
});

test('testgetUtenteAutenticatoGVN', function () {
    $gestoreservice = new GestoreService();
    

    $output = $gestoreservice->getUtenteAutenticato();
    $outputsessione = Session::get('Gestore');

    expect($output)->toBeNull();
    expect($outputsessione)->toBeNull();;
});

test('testgetUtenteAutenticatoGN', function () {
    $gestoreservice = new GestoreService();
    

    $output = $gestoreservice->getUtenteAutenticato();
    $outputsessione = Session::get('Gestore');

    expect($output)->toBeNull();
    expect($outputsessione)->toBeNull();;
});


test('testgetUtenteAutenticatoGVNV', function () {
    $gestoreservice = new GestoreService();
    
    // Inseriamo un dato di tipo sbagliato (stringa invece di oggetto Gestore)
    Session::put('Gestore', "ciao");

    // 1. Verifichiamo che il metodo ESPLODA (TypeError è un Throwable)
    expect(fn() => $gestoreservice->getUtenteAutenticato())
        ->toThrow(\TypeError::class); 

    // 2. Verifichiamo che in sessione ci sia ancora la stringa "ciao"
    // (L'errore avviene perché il dato c'è, ma è del tipo sbagliato!)
    $outputsessione = Session::get('Gestore');
    expect($outputsessione)->toBe("ciao");
});

