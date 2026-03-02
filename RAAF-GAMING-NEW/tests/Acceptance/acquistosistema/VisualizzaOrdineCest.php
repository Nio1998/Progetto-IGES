<?php

declare(strict_types=1);

namespace Tests\Acceptance\acquistosistema;

use Tests\Support\AcceptanceTester;

final class VisualizzaOrdineCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function visualizzaOrdine(AcceptanceTester $I)
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');
        $I->fillField(['name' => 'password'], 'veloce123');
        $I->click('.invio');

        $I->waitForElement('#dropdownMenuButton', 10);
        $I->click('#dropdownMenuButton');

        $I->waitForElement('a[href*="ordini"]', 10);
        $I->click('I miei ordini');

        $I->waitForElement('#peppino', 10);
        $I->see('I TUOI ORDINI', '#peppino');
    }
}
