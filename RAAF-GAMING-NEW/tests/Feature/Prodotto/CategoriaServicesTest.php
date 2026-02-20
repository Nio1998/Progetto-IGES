<?php

use App\Models\Prodotto\Categoria;
use App\Services\Prodotto\CategoriaService;
use Database\Seeders\TestCategoriaSeeder;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Collection\Collection;

uses()->group('CategoriaUnit', 'Unit');

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
        $schemaCategoria = base_path('tests/resources/init/categoria.sql');
        
        if (file_exists($schemaCategoria)) {
            // Creazione delle tabelle in memoria
            DB::unprepared(file_get_contents($schemaCategoria));
        } else {
            throw new \Exception("File SQL non trovato: {$schemaCategoria}");
        }
        
        // Esegui SOLO i seeder che ti servono§
        $this->seed(TestCategoriaSeeder::class);
        
        $dbInitialized = true;
    }
    
    // Inizia transazione per ogni test
    DB::beginTransaction();
    Session::flush();
    Cache::flush();
});

afterEach(function () {
    DB::rollback();
    Session::flush();
    Cache::flush();
});
/* PER ESSERE ATTIVATO DOBBIMO CAMBIARE IL METODO LOADMAGAZZINI DA PRIVATE A PUBLIC
test('testLoadCategorieDCDID', function () {
    $categorieCache = collect([
        new Categoria(['nome' => 'Arcade']),
    ])->keyBy('nome');

    Cache::put('Categorie', $categorieCache, 60);

    $categorie = new CategoriaService();
    $output = $categorie->loadCategorie();

    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->first()->nome)->toBe('Arcade');
});


test(' testLoadCategorieDNCDID', function () {
    $categorie = new CategoriaService();
    $output = $categorie->loadCategorie();
    
    // Test/Asserzioni
    expect($output)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    expect($output->get('Arcade')->nome)->toBe('Arcade');
    expect($output->get('Battle Royale')->nome)->toBe('Battle Royale');


});

test('testLoadCategorieDNCDNID', function () {
    DB::table('categoria')->delete();
    $categorie = new CategoriaService();
    $output = $categorie->loadCategorie();
    
    // Test/Asserzioni
    expect($output)->toBeEmpty();
});
*/

test('testAllElementsOASC', function () {

    $categorieCache = collect([
        new Categoria(['nome' => 'Arcade']),
        new Categoria(['nome' => 'Battle Royale'])
    ])->keyBy('nome');

    Cache::put('Categorie', $categorieCache, 60);

    $categoriaService = new CategoriaService();
    $ordinamento = "nome asc";
    $output = $categoriaService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/CategoriaNomeAsc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});

test('testAllElementsODESC', function () {

    $categorieCache = collect([
        new Categoria(['nome' => 'Arcade']),
        new Categoria(['nome' => 'Battle Royale'])
    ])->keyBy('nome');

    Cache::put('Categorie', $categorieCache, 60);

    $categoriaService = new CategoriaService();
    $ordinamento = "nome desc";
    $output = $categoriaService->allElements($ordinamento);
    $outputArray = $output->values()->all();
    $expected = require base_path('tests/resources/expected/CategoriaNomeDesc.php');

    expect($output)->toHaveCount(count($expected));
    
    foreach ($expected as $index => $expectedRow) {
        foreach ($expectedRow as $campo => $valore) {
            $actualValue = $outputArray[$index]->$campo;
            expect($actualValue)->toBe($valore);
        }
    }
});


test('testAllElementsONONVAL', function () {

    $categorieCache = collect([
        new Categoria(['nome' => 'Arcade']),
        new Categoria(['nome' => 'Battle Royale'])
    ])->keyBy('nome');

    Cache::put('Categorie', $categorieCache, 60);

    $categoriaService = new CategoriaService();
    $ordinamento = "Napoli ssc";

    expect(fn() => $categoriaService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Ordinamento scritto in modo errato");
});

test('testAllElementsOV', function () {

    $categorieCache = collect([
        new Categoria(['nome' => 'Arcade']),
        new Categoria(['nome' => 'Battle Royale'])
    ])->keyBy('nome');

    Cache::put('Categorie', $categorieCache, 60);

    $categoriaService = new CategoriaService();
    $ordinamento = "";

    expect(fn() => $categoriaService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});


test('testAllElementsON', function () {

    $categorieCache = collect([
        new Categoria(['nome' => 'Arcade']),
        new Categoria(['nome' => 'Battle Royale'])
    ])->keyBy('nome');

    Cache::put('Categorie', $categorieCache, 60);

    $categoriaService = new CategoriaService();
    $ordinamento = null;

    expect(fn() => $categoriaService->allElements($ordinamento))
        ->toThrow(\InvalidArgumentException::class, "Inserito un ordinamento null o vuoto");
});

// ----------------------ALL ELEMENTS----------------------
