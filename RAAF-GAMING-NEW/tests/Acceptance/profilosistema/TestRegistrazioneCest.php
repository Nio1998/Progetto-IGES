<?php

declare(strict_types=1);

namespace Tests\Acceptance\profilosistema;

use Tests\Support\AcceptanceTester;

final class TestRegistrazioneCest
{
 public function testRegistrazioneEseguito(AcceptanceTester $I): void
    {
        $I->amOnPage('/registrazione'); // Modifica se l'URL è diverso

        // --- INPUT (Test Case 6.1.12.1) ---
        // Uso gli ID e i selettori CSS basati sulla tua View
        $I->fillField('#validationCustom01', 'Francesco');      // Nome
        $I->fillField('#validationCustom02', 'Peluso');        // Cognome
        $I->fillField('#datadinascita', '11/09/2000');         // Data di Nascita
        $I->fillField('#validationCustom07', '8765432341234567'); // Codice Carta
        
        // NOTA: Come concordato, inserisco una data futura per superare i blocchi JS/Server
        // L'input originale era 16/02/2022
        $I->fillField('#data_scadenza', '16/02/2027');         // Data Scadenza
        
        $I->fillField('#validationCustom08', '123');           // CVV
        $I->fillField('#validationCustomUsername', 'peluso.francesco24@gmail.com'); // Email
        $I->fillField('#validationCustom04', 'veloce123');     // Password

        // --- INVIO ---
        $I->click('REGISTRATI'); // Clicca sul tasto submit

        // --- ORACOLO ---
        // 1. Verifica che siamo tornati al login
        $I->seeInCurrentUrl('/login');
        
        // 2. Verifica che non ci siano messaggi di errore (opzionale ma utile)
        $I->dontSee('Il campo email è già presente', 'p[name="messaggioerrore"]');
    }

    public function testRegistrazioneErroreEmailEsistente(AcceptanceTester $I): void
    {

    }
}
