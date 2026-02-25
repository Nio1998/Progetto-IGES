<?php

declare(strict_types=1);

namespace Tests\Acceptance\prodottosistema;

use Tests\Support\AcceptanceTester;

final class TestAggiuntaAlCarrelloCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testAggiuntaAlCarrelloProdottoGiaAggiunto(AcceptanceTester $I)
    {
        // 1. Apri la pagina iniziale
        $I->amOnPage('/');

        // 2. Clicca sul primo prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 3. Clicca sul pulsante per aggiungere
        $I->click('.btn > #sostituisciCarrello');

        // 4. Verifica il popup di successo e accettalo
        $I->wait(5);
        $I->seeInPopup('Aggiunta nel carrello fatta con successo!');
        $I->acceptPopup();

        // 5. Clicca sul logo per tornare alla home
        $I->click('.w-\[75px\]');

        // 6. Clicca di nuovo sul titolo dello stesso prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 7. Clicca sull'icona del carrello per riaggiungerlo
        $I->click('.btn > #sostituisciCarrello');

        // 8. Verifica l'alert che avvisa che il prodotto è già nel carrello
        $I->wait(5);
        $I->seeInPopup('Hai gia questo prodotto nell carrello');
        $I->acceptPopup();
    }

    public function testAggiuntaAlCarrelloProdottoNonDisponibile(AcceptanceTester $I)
    {
        $I->amOnPage('/prodotto/dettaglio?codice=9');

        // 2 | click | css=.pl-2
        $I->click('.btn > .fas');

        // 3 | assertAlert | Prodotto non disponibile in magazzino
        $I->wait(5);
        $I->seeInPopup('Prodotto non disponibile in magazzino');
        $I->acceptPopup();
    }

    public function testAggiuntaAlCarrelloProdottoAggiunto(AcceptanceTester $I)
    {
        // 1. Apri la pagina iniziale
        $I->amOnPage('/');

        // 2. Clicca sul primo prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 3. Clicca sul pulsante per aggiungere
        $I->click('.btn > #sostituisciCarrello');

        // 4. Verifica il popup di successo e accettalo
        $I->wait(5);
        $I->seeInPopup('Aggiunta nel carrello fatta con successo!');
        $I->acceptPopup();
    }


}
