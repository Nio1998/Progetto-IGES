<?php

declare(strict_types=1);

namespace Tests\Acceptance\acquistosistema;

use Tests\Support\AcceptanceTester;

final class GestioneOrdineCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function ordineNonSpedito(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina login admin
        $I->amOnPage('/admin');

        // 2 | Inserimento credenziali admin
        $I->waitForElement('#exampleInputEmail1', 10);
        $I->fillField('#exampleInputEmail1', 'ordine@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');

        // 3 | Click sul bottone Accedi
        $I->click('.btn');

        // 4 | Click su spedizioneProdotto per mostrare la tabella ordini
        $I->waitForElement('#spedizioneProdotto', 10);
        $I->click('#spedizioneProdotto');

        // 5 | Inserimento data non valida (passata) tramite executeInSelenium
        $I->waitForElement('.idata0', 10);
        $I->executeInSelenium(function ($webdriver) {
        $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector('.idata0'));
        $input->sendKeys('12-06-2021');
        });
        $I->wait(10);

       // 6 | Submit del form tramite JavaScript per triggerare onsubmit
        $I->waitForElement('.btn.ml-3', 10);
        $I->executeInSelenium(function ($webdriver) {
            $form = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector('#ordine form'));
            $webdriver->executeScript('arguments[0].dispatchEvent(new Event("submit", {bubbles: true, cancelable: true}));', [$form]);
        });

        // Attendi che il JavaScript esegua il controllo
        $I->wait(2);

        // 7 | Verifica che il bordo del campo data sia rosso (data non valida)
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector('.idata0'));
            $borderColor = $input->getCSSValue('border-color');
            \PHPUnit\Framework\Assert::assertEquals('rgb(255, 0, 0)', $borderColor);
        });
    }

    public function ordineSpedito(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina login admin
        $I->amOnPage('/admin');

        // 2 | Inserimento credenziali admin
        $I->waitForElement('#exampleInputEmail1', 10);
        $I->fillField('#exampleInputEmail1', 'ordine@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');

        // 3 | Click sul bottone Accedi
        $I->click('.btn');

        // 4 | Click su spedizioneProdotto per mostrare la tabella ordini
        $I->waitForElement('#spedizioneProdotto', 10);
        $I->click('#spedizioneProdotto');

        // 5 | Inserimento data valida (futura)
        $I->waitForElement('.idata0', 10);
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector('.idata0'));
            $input->sendKeys('12-04-2026');
        });

        // 6 | Click su Conferma
        $I->waitForElement('.btn.ml-3', 10);
        $I->click('.btn.ml-3');

        // 7 | Verifica messaggio ordine spedito con successo
        $I->waitForText('Ordine spedito con successo!', 10);
        $I->see('Ordine spedito con successo!');
    }
}