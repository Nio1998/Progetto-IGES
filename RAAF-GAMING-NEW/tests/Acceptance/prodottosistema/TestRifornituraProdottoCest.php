<?php

declare(strict_types=1);

namespace Tests\Acceptance\prodottosistema;

use Tests\Support\AcceptanceTester;
use Facebook\WebDriver\WebDriverKeys;

final class TestRifornituraProdottoCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testRifornituraProdottoOK(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#esistenteProdotto', 10);
        $I->checkOption('#esistenteProdotto');
        $I->wait(1);

        $I->fillField(['name' => 'quantita'], '1');
        $I->click('#esistente table tbody tr:first-child .btn');
        $I->wait(3);

        $I->waitForElement('[name="successo"]', 10);
        $I->see('Prodotto Rifornito con Successo', '[name="successo"]');
    }

    public function testRifornituraProdottoQuantitaNo(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#esistenteProdotto', 10);
        $I->checkOption('#esistenteProdotto');
        $I->wait(1);

        $I->fillField(['name' => 'quantita'], '-20');
        $I->click('#esistente table tbody tr:first-child .btn');
        $I->wait(3);

        $I->dontSeeElement('[name="successo"]');
        $I->dontSeeElement('[name="errore"]');
    }

    public function testRifornituraProdottoCapienzaNO(AcceptanceTester $I)
	{
		$I->amOnPage('/admin');
		$I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
		$I->fillField('#exampleInputPassword1', 'veloce123');
		$I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

		$I->waitForElement('#esistenteProdotto', 10);
		$I->checkOption('#esistenteProdotto');
		$I->wait(1);

		$I->fillField('#esistente table tbody tr:nth-child(2) td input[name="quantita"]', '5000');
		$I->click('#esistente table tbody tr:nth-child(2) .btn');
		$I->wait(3);

		$I->waitForElement('[name="errore"]', 10);
		$I->see('Capienza non disponibile', '[name="errore"]');
	}
}
