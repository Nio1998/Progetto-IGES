<?php

declare(strict_types=1);

namespace Tests\Acceptance\carrellosistema;

use Tests\Support\AcceptanceTester;

final class TestVisualizzaCarrelloCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testVisualizzaCarrelloCarrelloVuoto(AcceptanceTester $I): void
    {
        // 1 | Vai alla home
        $I->amOnPage('/');

        // 2 | Click sul carrello nel navbar
        $I->waitForElement('#sostituisciCarrello', 10);
        $I->click('#sostituisciCarrello');

        // 3 | Verifica di essere sulla pagina carrello
        $I->seeInCurrentUrl('/carrello');

        // 4 | Verifica messaggio nessun prodotto nel carrello
        $I->waitForElement('[name="NonHaiProdotti"]', 10);
        $I->see('NON HAI NESSUN PRODOTTO NEL CARRELLO!', '[name="NonHaiProdotti"]');
    }

    public function testVisualizzaCarrelloCarrelloConElementi(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina del prodotto con codice=1
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

        // 5 | Verifica che il messaggio "nessun prodotto" NON sia presente
        $I->waitForElement('.product', 10);
        $I->dontSee('NON HAI NESSUN PRODOTTO NEL CARRELLO!');
    }
}