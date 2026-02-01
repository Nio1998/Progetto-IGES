<?php

use App\Models\Profilo\CartaDiCredito;
use App\Models\Profilo\CartaFedelta;
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

// ----------------------RICERCA PER CHIAVE----------------------
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
// ----------------------RICERCA PER CHIAVE----------------------

// ----------------------GET UTENTE AUTENTICATO----------------------
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
// ----------------------GET UTENTE AUTENTICATO----------------------

// ----------------------LOGOUT UTENTE----------------------
test('testLogoutUtenteCV', function () {

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

    $clienteService->logoutUtente();
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->toBeNull();
});

test('testLogoutUtenteCVN', function () {

    $clienteService = new ClienteService();
    Session::put('Cliente',null);

    $clienteService->logoutUtente();
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->toBeNull();
});

test('testLogoutUtenteCN', function () {

    $clienteService = new ClienteService();

    $clienteService->logoutUtente();
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->toBeNull();
});

test('testLogoutUtenteCVNV', function () {

    $clienteService = new ClienteService();
    Session::put('Cliente','nonvalido');

    $clienteService->logoutUtente();
    $outputSessione = Session::get('Cliente');

    expect($outputSessione)->toBeNull();
});
// ----------------------LOGOUT UTENTE----------------------

// ----------------------GET CRYPTED PASSWORD----------------------
test('testGetCryptedPasswordSV', function () {

    $clienteService = new ClienteService();
    $password = "abcd";
    $output = $clienteService->getCryptedPassword($password);

    expect($output)->toBe("e2fc714c4727ee9395f324cd2e7f331f");
});

test('testGetCryptedPasswordEmpty', function () {

    $clienteService = new ClienteService();
    $password = "";
    expect(fn() => $clienteService->getCryptedPassword($password))
        ->toThrow(\InvalidArgumentException::class, "Password null o vuota");
});

test('testGetCryptedPasswordNull', function () {

    $clienteService = new ClienteService();
    $password = null;
    expect(fn() => $clienteService->getCryptedPassword($password))
        ->toThrow(\InvalidArgumentException::class, "Password null o vuota");
});
// ----------------------GET CRYPTED PASSWORD----------------------

// ----------------------CHECK PASSWORD----------------------
test('testCheckPasswordSVUVT', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "a71117b6a7c99548c37766a5e867fe9b";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";

    $password = "veloce123";
    $output = $clienteService->checkPassword($password,$cliente);
    expect($output)->toBe(true);
});

test('testCheckPasswordSVUVF', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "a71117b6a7c99548c37766a5e867fe9b";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";

    $password = "veloce1234";
    $output = $clienteService->checkPassword($password,$cliente);
    expect($output)->toBe(false);
});

test('testCheckPasswordSVUN', function () {

    $clienteService = new ClienteService();
    $cliente = null;
    $password = "veloce123";
    expect(fn() => $clienteService->checkPassword($password,$cliente))
        ->toThrow(\InvalidArgumentException::class, "Password o utente null");
});

test('testCheckPasswordSNUVF', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "a71117b6a7c99548c37766a5e867fe9b";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";
    $password = null;
    expect(fn() => $clienteService->checkPassword($password,$cliente))
        ->toThrow(\InvalidArgumentException::class, "Password o utente null");
});
// ----------------------CHECK PASSWORD----------------------

// ----------------------NEW INSERT----------------------
test('testNewInsertOK', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso26@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "a71117b6a7c99548c37766a5e867fe9b";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";

    $cartad = new CartaDiCredito();
    $cartad->codicecarta = '1234123412341239';
    $cartad->data_scadenza = '2028-12-12';
    $cartad->codice_cvv = '012';

    $cartaf = new CartaFedelta();
    $cartaf->codice = "1234567897";
    $cartaf->punti = 0;

    $clienteService->newInsert($cliente,$cartaf,$cartad);

    $expected = require base_path('tests/resources/expected/ClienteNewInsert.php');

    $actual = DB::table('cliente')->get()->toArray();

    expect($actual)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            expect($actual[$index]->$campo)->toBe($valore);
        }
    }

    expect($cliente->cartafedelta)->not->toBeNull()
        ->and($cliente->cartafedelta->codice)->toBe($cartaf->codice)
        ->and($cliente->cartafedelta->punti)->toBe($cartaf->punti);
    
    expect($cliente->cartacredito)->not->toBeNull()
        ->and($cliente->cartacredito->codicecarta)->toBe($cartad->codicecarta)
        ->and($cliente->cartacredito->codice_cvv)->toBe($cartad->codice_cvv);

});

test('testNewInsertCartaFNull', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso26@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "a71117b6a7c99548c37766a5e867fe9b";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";

    $cartad = new CartaDiCredito();
    $cartad->codicecarta = '1234123412341239';
    $cartad->data_scadenza = '2028-12-12';
    $cartad->codice_cvv = '012';

    $cartaf = null;

    expect(fn() => $clienteService->newInsert($cliente,$cartaf,$cartad))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item o carta_fedelta o cartadicredito null");

});

test('testNewInsertNotOk', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso25@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "a71117b6a7c99548c37766a5e867fe9b";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";

    $cartad = new CartaDiCredito();
    $cartad->codicecarta = '1234123412341239';
    $cartad->data_scadenza = '2028-12-12';
    $cartad->codice_cvv = '012';

    $cartaf = new CartaFedelta();
    $cartaf->codice = "1234567897";
    $cartaf->punti = 0;

     expect(fn() => $clienteService->newInsert($cliente,$cartaf,$cartad))
        ->toThrow(\Illuminate\Database\UniqueConstraintViolationException::class);

});

test('testNewInsertCartaDNull', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "f.peluso26@gmail.com";
    $cliente->nome = "Francesco";
    $cliente->cognome = "Peluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "a71117b6a7c99548c37766a5e867fe9b";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";

    $cartad = null;

    $cartaf = new CartaFedelta();
    $cartaf->codice = "1234567897";
    $cartaf->punti = 0;

    expect(fn() => $clienteService->newInsert($cliente,$cartaf,$cartad))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item o carta_fedelta o cartadicredito null");

});

test('testNewInsertNull', function () {

    $clienteService = new ClienteService();
    $cliente = null;

    $cartad = new CartaDiCredito();
    $cartad->codicecarta = '1234123412341239';
    $cartad->data_scadenza = '2028-12-12';
    $cartad->codice_cvv = '012';

    $cartaf = new CartaFedelta();
    $cartaf->codice = "1234567897";
    $cartaf->punti = 0;

    expect(fn() => $clienteService->newInsert($cliente,$cartaf,$cartad))
        ->toThrow(\InvalidArgumentException::class, "Inserito un item o carta_fedelta o cartadicredito null");

});
// ----------------------NEW INSERT----------------------

// ----------------------ALL ELEMENTS----------------------
test('testAllElementsOASC', function () {

    $clienteService = new ClienteService();
    $ordinamento = "email asc";
    $output = $clienteService->allElements($ordinamento);
    $output->toArray();
    $expected = require base_path('tests/resources/expected/ClienteEmailAsc.php');

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
})->group('Regression');

test('testAllElementsODESC', function () {

    $clienteService = new ClienteService();
    $ordinamento = "email desc";
    $output = $clienteService->allElements($ordinamento);
    $output->toArray();
    $expected = require base_path('tests/resources/expected/ClienteEmailDesc.php');

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
})->group('Regression');

test('testAllElementsNonValido', function () {

    $clienteService = new ClienteService();
    $ordinamento = "Peppe dasc";

    expect(fn() => $clienteService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "ordinamento scritto in modo errato");
})->group('Regression');

test('testAllElementsVuoto', function () {

    $clienteService = new ClienteService();
    $ordinamento = "";

    expect(fn() => $clienteService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
})->group('Regression');

test('testAllElementsNull', function () {

    $clienteService = new ClienteService();
    $ordinamento = null;

    expect(fn() => $clienteService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
})->group('Regression');
// ----------------------ALL ELEMENTS----------------------

// ----------------------DO UPDATE----------------------
test('testdoUpdateCLNDB', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "b.veluso25@gmail.com";
    $cliente->nome = "Brancesco";
    $cliente->cognome = "Veluso";
    $cliente->data_di_nascita = "2000-08-24";
    $cliente->password = "fdae384f2bd81b9d7ccb92acd17dd4d5";
    $cliente->carta_fedelta = "1234567897";
    $cliente->cartadicredito = "1234123412341237";

    $clienteService->doUpdate($cliente);

    $actual = DB::table('cliente')->get()->toArray();

    $expected = require base_path('tests/resources/expected/ClienteDoUpdateNonPresente.php');

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
        ->and($outputSessione->email)->toBe("b.veluso25@gmail.com")
        ->and($outputSessione->nome)->toBe("Brancesco")
        ->and($outputSessione->cognome)->toBe("Veluso")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("2000-08-24")
        ->and($outputSessione->password)->toBe("fdae384f2bd81b9d7ccb92acd17dd4d5")
        ->and($outputSessione->carta_fedelta)->toBe("1234567897")
        ->and($outputSessione->cartadicredito)->toBe("1234123412341237");

});

test('testdoUpdateCLPD', function () {

    $clienteService = new ClienteService();
    $cliente = Cliente::where("email","f.peluso25@gmail.com")->first();
    Session::put('Cliente',$cliente);

    $cliente->password = "05ec5bed5c2756b6b305b7fcd7e4b6df";
    $clienteService->doUpdate($cliente);

    $actual = DB::table('cliente')->get()->toArray();

    $expected = require base_path('tests/resources/expected/ClienteDoUpdatePresente.php');

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

test('testdoUpdateCLNULL', function () {

    $clienteService = new ClienteService();
    $cliente = null;
    expect(fn() =>     $clienteService->doUpdate($cliente))
            ->toThrow(\InvalidArgumentException::class, "Inserito un item null");

    $actual = DB::table('cliente')->get()->toArray();

    $expected = require base_path('tests/resources/expected/ClienteDoUpdateNonPresente.php');

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

});

test('testdoUpdateCLNDBS', function () {

    $clienteService = new ClienteService();
    $cliente = new Cliente();
    $cliente->email = "M.Bros@gmail.com";
    $cliente->nome = "Mario";
    $cliente->cognome = "Bros";
    $cliente->data_di_nascita = "1995-03-28";
    $cliente->password = "0cb911589561cb068bf35a76ce4df249";
    $cliente->carta_fedelta = "1234767297";
    $cliente->cartadicredito = "1238363412341237";

    Session::put('Cliente',$cliente);

    $clienteService->doUpdate($cliente);

    $actual = DB::table('cliente')->get()->toArray();

    $expected = require base_path('tests/resources/expected/ClienteDoUpdateNonPresente.php');

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
        ->and($outputSessione->email)->toBe("M.Bros@gmail.com")
        ->and($outputSessione->nome)->toBe("Mario")
        ->and($outputSessione->cognome)->toBe("Bros")
        ->and($outputSessione->data_di_nascita->format('Y-m-d'))->toBe("1995-03-28")
        ->and($outputSessione->password)->toBe("0cb911589561cb068bf35a76ce4df249")
        ->and($outputSessione->carta_fedelta)->toBe("1234767297")
        ->and($outputSessione->cartadicredito)->toBe("1238363412341237");

});

test('testdoUpdateCLPDNS', function () {

    $clienteService = new ClienteService();
    $cliente = Cliente::where("email","f.peluso25@gmail.com")->first();
    $cliente->password = "05ec5bed5c2756b6b305b7fcd7e4b6df";
    $clienteService->doUpdate($cliente);

    $actual = DB::table('cliente')->get()->toArray();

    $expected = require base_path('tests/resources/expected/ClienteDoUpdatePresente.php');

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

// ----------------------DO UPDATE----------------------