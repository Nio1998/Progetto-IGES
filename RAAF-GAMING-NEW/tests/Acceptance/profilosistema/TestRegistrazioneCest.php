<?php

declare(strict_types=1);

namespace Tests\Acceptance\profilosistema;

use Tests\Support\AcceptanceTester;

final class TestRegistrazioneCest
{
    public function testRegistrazioneEseguito(AcceptanceTester $I): void
    {
        $I->amOnPage('/registrazione');

        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');
        $I->fillField('#validationCustom02', 'Peluso');

        // Data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->executeScript(
                'document.getElementById("datadinascita").value = "2000-09-11";'
            );
        });

        $I->fillField('#validationCustom07', '8765432341234567');

        // Data scadenza
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->executeScript(
                'document.getElementById("data_scadenza").value = "2027-02-16";'
            );
        });

        $I->fillField('#validationCustom08', '123');
        $I->fillField('#validationCustomUsername', 'utente.test.nuovo999@gmail.com');
        $I->fillField('#validationCustom04', 'veloce123');

        $I->click('.invio');

        $I->seeInCurrentUrl('/login');
    }
    /*
    public function testRegistrazioneErroreEmailEsistente(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 5 | Inserimento numero carta
        $I->fillField('#validationCustom07', '8765432341234567');

        // 6 | Inserimento data scadenza carta (futura)
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 7 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 8 | Inserimento email già esistente
        $I->fillField('#validationCustomUsername', 'f.peluso25@gmail.com');

        // 9 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore email già esistente
        $I->waitForText('Sei già iscritto al nostro sito!', 10);
        $I->see('Sei già iscritto al nostro sito!');
    }
        */
/*
    public function testRegistrazioneErroreCartaEsistente(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 5 | Inserimento numero carta già esistente
        $I->fillField('#validationCustom07', '1234567891234567');

        // 6 | Inserimento data scadenza carta (futura)
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 7 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 8 | Inserimento email nuova
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 9 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore carta già esistente
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Non puoi registrarti con questa carta', '[name="messaggioerrore"]');
    }
*/
    public function testRegistrazioneErroreNome(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Rimuovi required dal campo nome per permettere il submit con nome vuoto
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->executeScript(
                'document.getElementById("validationCustom01").removeAttribute("required");'
            );
        });

        // 3 | Inserimento nome vuoto
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', '');

        // 4 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 5 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 6 | Inserimento numero carta
        $I->fillField('#validationCustom07', '8765432341234567');

        // 7 | Inserimento data scadenza carta
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 8 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 9 | Inserimento email
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 10 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 11 | Click su Registrati
        $I->click('.invio');

        // 12 | Verifica messaggio errore nome non valido
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Hai inserito un nome non valido', '[name="messaggioerrore"]');
    }

    public function testRegistrazioneErroreCognome(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Rimuovi required dal campo cognome per permettere il submit con cognome vuoto
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->executeScript(
                'document.getElementById("validationCustom02").removeAttribute("required");'
            );
        });

        // 3 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 4 | Inserimento cognome vuoto
        $I->fillField('#validationCustom02', '');

        // 5 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 6 | Inserimento numero carta
        $I->fillField('#validationCustom07', '8765432341234567');

        // 7 | Inserimento data scadenza carta
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 8 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 9 | Inserimento email
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 10 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 11 | Click su Registrati
        $I->click('.invio');

        // 12 | Verifica messaggio errore cognome non valido
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Hai inserito un cognome non valido', '[name="messaggioerrore"]');
    }

    public function testRegistrazioneErroreEta(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita (minorenne - 2020)
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2020');
        });

        // 5 | Inserimento numero carta
        $I->fillField('#validationCustom07', '8765432341234567');

        // 6 | Inserimento data scadenza carta
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 7 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 8 | Inserimento email
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 9 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore età
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see("Non hai l'età per registrarti", '[name="messaggioerrore"]');
    }

    public function testRegistrazioneErroreCarta(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 5 | Inserimento numero carta non valido (contiene lettere)
        $I->fillField('#validationCustom07', '8765b4323a234567');

        // 6 | Inserimento data scadenza carta
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 7 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 8 | Inserimento email
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 9 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore carta non valida
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Hai inserito un codice carta non valida', '[name="messaggioerrore"]');
    }

    public function testRegistrazioneErroreDataScadenza(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 5 | Inserimento numero carta valido
        $I->fillField('#validationCustom07', '8765432341234567');

        // 6 | Inserimento data scadenza carta non valida (già scaduta)
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('8/01/2022');
        });

        // 7 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 8 | Inserimento email
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 9 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore data scadenza non valida
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Data scadenza non valida', '[name="messaggioerrore"]');
    }

    public function testRegistrazioneErroreCVV(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita
        $I->fillField('#datadinascita', '11/09/2000');

        // 5 | Inserimento numero carta valido
        $I->fillField('#validationCustom07', '8765432341234567');

        // 6 | Inserimento data scadenza carta valida
        $I->fillField('#data_scadenza', '16/02/2027');

        // 7 | Inserimento CVV non valido (contiene lettere)
        $I->fillField('#validationCustom08', '12a');

        // 8 | Inserimento email
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 9 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore CVV non valido
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Hai inserito un CVV non valido', '[name="messaggioerrore"]');
    }

    public function testRegistrazioneErroreEmail(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 5 | Inserimento numero carta valido
        $I->fillField('#validationCustom07', '8765432341234567');

        // 6 | Inserimento data scadenza carta (futura)
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 7 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 8 | Cambia tipo campo email e inserisci email non valida
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->executeScript(
                'document.getElementById("validationCustomUsername").type = "text";'
            );
        });
        $I->fillField('#validationCustomUsername', 'abc.com');

        // 9 | Inserimento password
        $I->fillField('#validationCustom04', 'veloce123');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore email non valida
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see("Hai inserito un'Email non valida", '[name="messaggioerrore"]');
    }

    public function testRegistrazioneErrorePassword(AcceptanceTester $I): void
    {
        // 1 | Vai alla pagina di registrazione
        $I->amOnPage('/registrazione');

        // 2 | Inserimento nome
        $I->waitForElement('#validationCustom01', 10);
        $I->fillField('#validationCustom01', 'Francesco');

        // 3 | Inserimento cognome
        $I->fillField('#validationCustom02', 'Peluso');

        // 4 | Inserimento data di nascita
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('datadinascita'));
            $input->sendKeys('11/09/2000');
        });

        // 5 | Inserimento numero carta valido
        $I->fillField('#validationCustom07', '8765432341234567');

        // 6 | Inserimento data scadenza carta (futura)
        $I->executeInSelenium(function ($webdriver) {
            $input = $webdriver->findElement(\Facebook\WebDriver\WebDriverBy::id('data_scadenza'));
            $input->sendKeys('16/02/2027');
        });

        // 7 | Inserimento CVV
        $I->fillField('#validationCustom08', '123');

        // 8 | Inserimento email
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com');

        // 9 | Rimuovi required dal campo password e lascialo vuoto
        $I->executeInSelenium(function ($webdriver) {
            $webdriver->executeScript(
                'document.getElementById("validationCustom04").removeAttribute("required");'
            );
        });
        $I->fillField('#validationCustom04', '');

        // 10 | Click su Registrati
        $I->click('.invio');

        // 11 | Verifica messaggio errore password non valida
        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Hai inserito una password non valida', '[name="messaggioerrore"]');
    }
}
