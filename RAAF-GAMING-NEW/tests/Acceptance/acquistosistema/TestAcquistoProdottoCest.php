<?php

declare(strict_types=1);

namespace Tests\Acceptance\carrellosistema;

use Tests\Support\AcceptanceTester;

final class TestAcquistoDeiProdottiCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testAcquistoDeiProdottiNessunProdotto(AcceptanceTester $I): void
    {
        // 1 | open
        $I->amOnPage('/carrello');

        // 2 | click .btn-outline-warning
        $I->click('.btn-outline-warning');

        // 3 | assertAlert
        $I->seeInPopup('Non hai prodotti nel carrello');
    }


    public function testAcquistoDeiProdottiRiuscito(AcceptanceTester $I): void
    {
        // 1 | Login
        $I->amOnPage('/login');
        $I->waitForElement(['name' => 'email'], 10);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        // 2 | Click sul primo prodotto in home
        $I->waitForElement('.row:nth-child(1) li:nth-child(1) .card__title', 10);
        $I->click('.row:nth-child(1) li:nth-child(1) .card__title');

        // 3 | Click icona carrello nella pagina del gioco (id="Carrello")
        $I->waitForElement('#Carrello', 10);
        $I->click('#Carrello');

        // 4 | Gestione popup aggiunta carrello
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->wait(10, 500)->until(
                \Facebook\WebDriver\WebDriverExpectedCondition::alertIsPresent()
            );
        });
        $I->seeInPopup('Aggiunta nel carrello fatta con successo!');
        $I->acceptPopup();

        // 5 | Click sul carrello nel navbar (id="sostituisciCarrello") per andare alla pagina carrello
        $I->waitForElement('#sostituisciCarrello', 10);
        $I->click('#sostituisciCarrello');

        // 6 | Inserimento indirizzo di consegna
        $I->waitForElement(['name' => 'indirizzodiconsegna'], 10);
        $I->fillField(['name' => 'indirizzodiconsegna'], 'viale traiano');

        // 7 | Conferma Acquisto
        $I->waitForElement('.btn-outline-warning', 10);
        $I->click('.btn-outline-warning');
    }

    public function testAcquistoDeiProdottiNonDisponibile(AcceptanceTester $I): void
    {
        // 1 | Login
        $I->amOnPage('/login');
        $I->waitForElement(['name' => 'email'], 10);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        // 2 | Click sul terzo prodotto in home
        $I->waitForElement('.row:nth-child(1) li:nth-child(3) .card__header', 10);
        $I->click('.row:nth-child(1) li:nth-child(3) .card__header');

        // 3 | Click icona carrello nella pagina del gioco (id="Carrello")
        $I->waitForElement('#Carrello', 10);
        $I->click('#Carrello');

        // 4 | Gestione popup aggiunta carrello
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->wait(10, 500)->until(
                \Facebook\WebDriver\WebDriverExpectedCondition::alertIsPresent()
            );
        });
        $I->seeInPopup('Aggiunta nel carrello fatta con successo!');
        $I->acceptPopup();

        // 5 | Click sul carrello nel navbar per andare alla pagina carrello
        $I->waitForElement('#sostituisciCarrello', 10);
        $I->click('#sostituisciCarrello');

        // 6 | Inserimento indirizzo di consegna
        $I->waitForElement(['name' => 'indirizzodiconsegna'], 10);
        $I->fillField(['name' => 'indirizzodiconsegna'], 'viale croce');

        // 7 | Conferma Acquisto
        $I->waitForElement('.btn-outline-warning', 10);
        $I->click('.btn-outline-warning');

        // 8 | Verifica messaggio prodotto non disponibile
        $I->waitForElement('[name="prodottoNonDisponibile"]', 10);
        $I->see("Qualche o tutti i prodotti nel carrello non sono piu' disponibili", '[name="prodottoNonDisponibile"]');
    }

    public function testAcquistoDeiProdottiIndirizzoDiConsegnaNonValido(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina del prodotto con id=1
        $I->amOnPage('/prodotto/dettaglio?codice=1');

        // 2 | Click icona carrello nella pagina del gioco (id="Carrello")
        $I->waitForElement('#Carrello', 10);
        $I->click('#Carrello');

        // 3 | Gestione popup aggiunta carrello
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->wait(10, 500)->until(
                \Facebook\WebDriver\WebDriverExpectedCondition::alertIsPresent()
            );
        });
        $I->seeInPopup('Aggiunta nel carrello fatta con successo!');
        $I->acceptPopup();

        // 4 | Click sul carrello nel navbar per andare alla pagina carrello
        $I->waitForElement('#sostituisciCarrello', 10);
        $I->click('#sostituisciCarrello');

        // 5-6 | Inserimento indirizzo di consegna non valido
        $I->waitForElement(['name' => 'indirizzodiconsegna'], 10);
        $I->fillField(['name' => 'indirizzodiconsegna'], 'fgfffggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg');

        // 7 | Conferma Acquisto
        $I->waitForElement('.btn-outline-warning', 10);
        $I->click('.btn-outline-warning');

        // 8 | Verifica alert indirizzo non valido
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->wait(10, 500)->until(
                \Facebook\WebDriver\WebDriverExpectedCondition::alertIsPresent()
            );
        });
        $I->seeInPopup('INDIRIZZO DI CONSEGNA NON VALIDO!');
        $I->acceptPopup();
    }

    public function testVisualizzaCarrelloNonAutenticato(AcceptanceTester $I): void
    {
        // 1 | Login
        $I->amOnPage('/login');
        $I->waitForElement(['name' => 'email'], 10);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        // 2 | Click .fa-user-astronaut per aprire menu utente
        $I->waitForElement('.fa-user-astronaut', 5);
        $I->click('.fa-user-astronaut');

        // 3 | Click LogOut
        $I->click('LogOut');

        // 5 | Click sul primo prodotto in home
        $I->waitForElement('.row:nth-child(1) li:nth-child(1) .card__title', 10);
        $I->click('.row:nth-child(1) li:nth-child(1) .card__title');

        // 6 | Click icona carrello nella pagina del gioco (id="Carrello")
        $I->waitForElement('#Carrello', 10);
        $I->click('#Carrello');

        // 7 | Gestione popup aggiunta carrello
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->wait(10, 500)->until(
                \Facebook\WebDriver\WebDriverExpectedCondition::alertIsPresent()
            );
        });
        $I->seeInPopup('Aggiunta nel carrello fatta con successo!');
        $I->acceptPopup();

        // 8 | Click sul carrello nel navbar per andare alla pagina carrello
        $I->waitForElement('#sostituisciCarrello', 5);
        $I->click('#sostituisciCarrello');

        // 9 | Inserimento indirizzo di consegna
        $I->waitForElement(['name' => 'indirizzodiconsegna'], 5);
        $I->fillField(['name' => 'indirizzodiconsegna'], 'viale croce');

        // 10 | Conferma Acquisto
        $I->waitForElement('.btn-outline-warning', 5);
        $I->click('.btn-outline-warning');

        $I->seeInCurrentUrl('/');
    }

    
}