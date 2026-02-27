<?php

declare(strict_types=1);

namespace Tests\Acceptance\profilosistema;

use Tests\Support\AcceptanceTester;

final class TestProfiloCest
{
    public function testProfiloEseguito(AcceptanceTester $I): void
    {
        // --- 1. LOGIN (Prerequisito) ---
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');
        $I->seeCurrentUrlEquals('/');

        // --- 2. NAVIGAZIONE AL PROFILO ---
        $I->amOnPage('/profilo'); // Assicurati che la rotta sia questa

        // --- 3. INPUT (Dati del Test Case) ---
        // Nota: i nomi dei campi nel tuo HTML sono password1, password2, numeroCarta, cvvCarta, dataScadenza
        $I->fillField('#inputPassword1', 'veloce1234');
        $I->fillField('#inputPassword2', 'veloce1234');
        $I->fillField('#inputCarta1', '1234567899898989');
        $I->fillField('#inputCarta2', '123');
        $I->fillField('#inputCarta3', '05/04/2027'); // Vedi nota sotto sulla data

        // --- 4. INVIO (Click sul bottone che scatena AJAX) ---
        $I->click('INVIA'); 

        // --- 5. ORACOLO (Verifica Messaggio e Dati) ---
        // Siccome è AJAX, dobbiamo aspettare che il messaggio appaia
        $I->waitForElementVisible('#notifica', 5); 
        
        // Verifica che il messaggio contenga il testo atteso (come da oracolo)
        $I->see('Password modificata con successo!', '#notifica');

        // Verifica che la carta a video sia stata oscurata/aggiornata come previsto dal JS
        // Il tuo JS mette risposta.carta in #cartaAggiornata
        $I->see('****8989', '#cartaAggiornata');
    }

    public function testProfiloErroreCarta(AcceptanceTester $I): void
    {
        // --- 1. LOGIN (Prerequisito) ---
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        // --- 2. NAVIGAZIONE AL PROFILO ---
        $I->amOnPage('/profilo');

        // --- 3. INPUT (Dati del Test Case 6.1.6.2) ---
        $I->fillField('#inputPassword1', 'veloce1234');
        $I->fillField('#inputPassword2', 'veloce1234');
        $I->fillField('#inputCarta1', '1'); // INPUT ERRATO: Numero carta "1"
        $I->fillField('#inputCarta2', '123');
        $I->fillField('#inputCarta3', '05/04/2023');

        // --- 4. INVIO ---
        $I->click('INVIA');

        // --- 5. ORACOLO (Gestione Alert JS) ---
        // Poiché il tuo JS fa apparire un alert("Numero carta non valido (16 cifre)"),
        // dobbiamo dire a Codeception di verificare quel testo.
        $I->seeInPopup('Numero carta non valido (16 cifre)');
        $I->acceptPopup();
    }

    public function testProfiloErrorePWD(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputPassword1', 10);
        $I->fillField('#inputPassword1', 'ab1');
        $I->fillField('#inputPassword2', 'ab1');
        $I->fillField('#inputCarta1', '1234567899898989');
        $I->fillField('#inputCarta2', '123');
        $I->fillField('#inputCarta3', '05/02/2027');

        $I->click('.btn-aggiorna');
        $I->wait(2);

        $I->seeInPopup('password non valida');
        $I->acceptPopup();

        $I->wait(3);
        $I->see('****8989', '#cartaAggiornata');
    }

    public function testProfiloErroreData(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputPassword1', 10);
        $I->fillField('#inputPassword1', 'veloce1234');
        $I->fillField('#inputPassword2', 'veloce1234');
        $I->fillField('#inputCarta1', '1234567898989898');
        $I->fillField('#inputCarta2', '123');
        $I->fillField('#inputCarta3', '06/01/2022');

        $I->click('.btn-aggiorna');
        $I->wait(2);

        $I->seeInPopup("La carta di credito è scaduta");
        $I->acceptPopup();
    }

    public function testProfiloErroreCVV(AcceptanceTester $I): void
    {
        // 1. LOGIN
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        // 2. NAVIGAZIONE
        $I->amOnPage('/profilo');

        // 3. INPUT (Test Case 6.1.6.5)
        $I->fillField('#inputPassword1', 'veloce1234');
        $I->fillField('#inputPassword2', 'veloce1234');
        $I->fillField('#inputCarta1', '1234567898989898');
        $I->fillField('#inputCarta2', '1234'); // INPUT ERRATO (4 cifre)
        
        $I->fillField('#inputCarta3', '05/04/2027'); 

        // 4. INVIO
        $I->click('INVIA');

        // 5. ORACOLO
        // Il driver intercetta l'alert JS e verifica il testo
        $I->seeInPopup('CVV non valido (3 cifre)');
        $I->acceptPopup();
    }

    public function testProfiloErroreSoloPWD(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputPassword1', 10);
        $I->fillField('#inputPassword1', 'v1');
        $I->fillField('#inputPassword2', 'v1');

        $I->click('.btn-aggiorna');
        $I->wait(2);

        $I->seeInPopup('password non valida');
        $I->acceptPopup();
    }

    public function testProfiloEseguitoSoloPWD(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputPassword1', 10);
        $I->fillField('#inputPassword1', 'veloce1234');
        $I->fillField('#inputPassword2', 'veloce1234');

        $I->click('.btn-aggiorna');
        $I->wait(3);

        $I->waitForElement('#notifica', 10);
        $I->see('Password modificata con successo!', '#notifica');
    }

    public function testProfiloEseguitoSoloCarta(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputCarta1', 10);
        $I->fillField('#inputCarta1', '1234567898989898');
        $I->fillField('#inputCarta2', '123');
        $I->fillField('#inputCarta3', '05/02/2027');

        $I->click('.btn-aggiorna');
        $I->wait(3);

        $I->waitForElement('#cartaAggiornata', 10);
        $I->see('****9898', '#cartaAggiornata');
    }

    public function testProfiloErroreSoloCVV(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputCarta1', 10);
        $I->fillField('#inputCarta1', '1234567898989898');
        $I->fillField('#inputCarta2', '1234');
        $I->fillField('#inputCarta3', '07/01/2026');

        $I->click('.btn-aggiorna');
        $I->wait(2);

        $I->seeInPopup('Hai inserito un cvv non valido');
        $I->acceptPopup();
    }

    public function testProfiloErroreSoloData(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputCarta1', 10);
        $I->fillField('#inputCarta1', '1234567898989898');
        $I->fillField('#inputCarta2', '123');
        $I->fillField('#inputCarta3', '07/01/2022');

        $I->click('.btn-aggiorna');
        $I->wait(2);

        $I->seeInPopup("La carta di credito è scaduta");
        $I->acceptPopup();
    }

    public function testProfiloErroreSoloCarta(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="profilo"]', 10);
        $I->click('Profilo');

        $I->waitForElement('#inputCarta1', 10);
        $I->executeJS("document.getElementById('inputCarta1').removeAttribute('maxlength')");
        $I->fillField('#inputCarta1', '12345678989898989');
        $I->fillField('#inputCarta2', '123');
        $I->fillField('#inputCarta3', '07/08/2027');

        $I->click('.btn-aggiorna');
        $I->wait(2);

        $I->seeInPopup('Hai inserito una carta non valida');
        $I->acceptPopup();
    }

}
