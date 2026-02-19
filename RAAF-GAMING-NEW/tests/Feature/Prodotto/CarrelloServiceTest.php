<?php

use App\Services\Prodotto\CarrelloService;
use App\Models\Prodotto\Prodotto;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;
uses()->group('CarrelloUnit', 'Unit');

beforeEach(function () {
    Session::flush();
});

afterEach(function () {
    Session::flush();
});


test('aggiungiAlCarrelloPNC', function () {

    $carrelloService = new CarrelloService();

    $prodotto = new Prodotto([
        'prezzo'             => 10.5,
        'sconto'             => 0,
        'data_uscita'        => '2021-12-25',
        'nome'               => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura'     => '2020-12-20',
        'fornitore'          => 'Sony',
        'gestore'            => 'prodotto@admin.com',
    ]);
    $prodotto->codice_prodotto = 1;

    $result = $carrelloService->aggiungiAlCarrello($prodotto);

    // Restituisce false perchè il prodotto non era già nel carrello
    expect($result)->toBeFalse();

    // Verifica che il carrello sia stato creato in sessione con il prodotto
    $carrello = Session::get('Carrello');

    expect($carrello)->not->toBeNull();
    expect($carrello)->toHaveCount(1);
    expect($carrello[0]->codice_prodotto)->toBe(1);
    expect($carrello[0]->nome)->toBe('FIFA');
});



test('aggiungiAlCarrelloPNCC', function () {

    $carrelloService = new CarrelloService();

    // Prodotto già nel carrello (FIFA)
    $prodotto1 = new Prodotto([
        'prezzo'             => 10.5,
        'sconto'             => 0,
        'data_uscita'        => '2021-12-25',
        'nome'               => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura'     => '2020-12-20',
        'fornitore'          => 'Sony',
        'gestore'            => 'prodotto@admin.com',
    ]);
    $prodotto1->codice_prodotto = 1;

    // Nuovo prodotto da aggiungere (PES)
    $prodotto2 = new Prodotto([
        'prezzo'             => 15.5,
        'sconto'             => 0,
        'data_uscita'        => '2020-12-22',
        'nome'               => 'PES',
        'quantita_fornitura' => 12,
        'data_fornitura'     => '2019-12-20',
        'fornitore'          => 'Activision',
        'gestore'            => 'prodotto@admin.com',
    ]);
    $prodotto2->codice_prodotto = 2;

    // Popolo il carrello con il primo prodotto (N=1)
    $carrelloService->aggiungiAlCarrello($prodotto1);

    // Aggiungo il secondo prodotto (N+1=2)
    $result = $carrelloService->aggiungiAlCarrello($prodotto2);

    // Restituisce false perchè PES non era già nel carrello
    expect($result)->toBeFalse();

    // Verifica che il carrello contenga N+1 prodotti
    $carrello = Session::get('Carrello');

    expect($carrello)->not->toBeNull();
    expect($carrello)->toHaveCount(2);
    expect($carrello->first()->codice_prodotto)->toBe(1);
    expect($carrello->last()->codice_prodotto)->toBe(2);
});


test('aggiungiAlCarrelloPC', function () {

    $carrelloService = new CarrelloService();

    $prodotto = new Prodotto([
        'prezzo'             => 10.5,
        'sconto'             => 0,
        'data_uscita'        => '2021-12-25',
        'nome'               => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura'     => '2020-12-20',
        'fornitore'          => 'Sony',
        'gestore'            => 'prodotto@admin.com',
    ]);
    $prodotto->codice_prodotto = 1;

    // Prima aggiunta → va in carrello
    $carrelloService->aggiungiAlCarrello($prodotto);

    // Seconda aggiunta dello stesso prodotto
    $result = $carrelloService->aggiungiAlCarrello($prodotto);

    // Restituisce true perchè il prodotto era già nel carrello
    expect($result)->toBeTrue();

    // Verifica che il carrello contenga ancora solo 1 prodotto (non duplicato)
    $carrello = Session::get('Carrello');

    expect($carrello)->toHaveCount(1);
});



test('svuotaCarrelloCV', function () {

    $carrelloService = new CarrelloService();

    // Popolo il carrello con un prodotto
    $prodotto = new Prodotto([
        'prezzo'             => 10.5,
        'sconto'             => 0,
        'data_uscita'        => '2021-12-25',
        'nome'               => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura'     => '2020-12-20',
        'fornitore'          => 'Sony',
        'gestore'            => 'prodotto@admin.com',
    ]);
    $prodotto->codice_prodotto = 1;

    $carrelloService->aggiungiAlCarrello($prodotto);

    // Verifica che il carrello esista prima di svuotarlo
    expect(Session::get('Carrello'))->not->toBeNull();

    // Svuota il carrello
    $carrelloService->svuotaCarrello();

    // Verifica che la chiave 'Carrello' sia stata rimossa dalla sessione
    expect(Session::get('Carrello'))->toBeNull();
});


test('svuotaCarrelloCN', function () {

    $carrelloService = new CarrelloService();

    // Verifica che il carrello sia già null prima di svuotarlo
    expect(Session::get('Carrello'))->toBeNull();

    // Svuota il carrello anche se è già vuoto
    $carrelloService->svuotaCarrello();

    // Verifica che la chiave 'Carrello' sia ancora null
    expect(Session::get('Carrello'))->toBeNull();
});


test('getProdottiCV', function () {

    $carrelloService = new CarrelloService();

    // Popolo il carrello con due prodotti
    $prodotto1 = new Prodotto([
        'prezzo'             => 10.5,
        'sconto'             => 0,
        'data_uscita'        => '2021-12-25',
        'nome'               => 'FIFA',
        'quantita_fornitura' => 12,
        'data_fornitura'     => '2020-12-20',
        'fornitore'          => 'Sony',
        'gestore'            => 'prodotto@admin.com',
    ]);
    $prodotto1->codice_prodotto = 1;

    $prodotto2 = new Prodotto([
        'prezzo'             => 15.5,
        'sconto'             => 0,
        'data_uscita'        => '2020-12-22',
        'nome'               => 'PES',
        'quantita_fornitura' => 12,
        'data_fornitura'     => '2019-12-20',
        'fornitore'          => 'Activision',
        'gestore'            => 'prodotto@admin.com',
    ]);
    $prodotto2->codice_prodotto = 2;

    $carrelloService->aggiungiAlCarrello($prodotto1);
    $carrelloService->aggiungiAlCarrello($prodotto2);

    // Recupera i prodotti dal carrello
    $output = $carrelloService->getProdottiCarrello();

    // Verifica che sia una collection con i prodotti inseriti
    expect($output)->toBeInstanceOf(Collection::class);
    expect($output)->toHaveCount(2);
    expect($output->first()->codice_prodotto)->toBe(1);
    expect($output->last()->codice_prodotto)->toBe(2);
});


test('getProdottiCN', function () {

    $carrelloService = new CarrelloService();

    // Carrello vuoto - nessun prodotto aggiunto
    $output = $carrelloService->getProdottiCarrello();

    // Verifica che sia una collection vuota
    expect($output)->toBeInstanceOf(Collection::class);
    expect($output)->toHaveCount(0);
    expect($output->isEmpty())->toBeTrue();
});