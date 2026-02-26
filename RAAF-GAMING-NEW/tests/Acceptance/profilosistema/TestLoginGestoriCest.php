<?php

declare(strict_types=1);

namespace Tests\Acceptance\profilosistema;

use Tests\Support\AcceptanceTester;
use Facebook\WebDriver\WebDriverKeys;

final class TestLoginGestoriCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testLoginFallitoPasswordOrdine(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'ordine@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce1234');
        $I->click('.btn');
        $I->wait(2);

        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Email/Password errata!', '[name="messaggioerrore"]');
    }

    public function testLoginFallitoEntrambiGestori(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'abc@gmail.com');
        $I->fillField('#exampleInputPassword1', 'veloce1234');
        $I->click('.btn');
        $I->wait(2);

        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Email/Password errata!', '[name="messaggioerrore"]');
    }

    public function testLoginEseguitoOrdine(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'ordine@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->wait(2);
        $I->seeInTitle('Ordini da gestire');
    }

    public function testLoginFallitoPasswordProdotto(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce1234');
        $I->click('.btn');
        $I->wait(2);

        $I->waitForElement('[name="messaggioerrore"]', 10);
        $I->see('Email/Password errata!', '[name="messaggioerrore"]');
    }

    public function testLoginEseguitoProdotto(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->wait(2);
        $I->seeInTitle('Pagina-Amministrazione');
    }

}
