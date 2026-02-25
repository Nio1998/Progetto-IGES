<?php

declare(strict_types=1);

namespace Tests\Acceptance\prodottosistema;

use Tests\Support\AcceptanceTester;

final class TestRicercaPerCategoriaCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testRicercaPerCategoriaOK(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->click('.navbar-toggler');
        $I->wait(2);
        $I->click('Sport');

        $I->waitForElement('.card__title', 10);
        $I->wait(2);

        $I->see('fifa 21', '.card__title');
    }

    public function testRicercaPerCategoriaNO(AcceptanceTester $I)
    {
        $I->amOnPage('/prodotto/categoria/abc');
        $I->wait(2);
        $I->seeElement('.card__title');
    }
}
