<?php

declare(strict_types=1);

namespace Tests\Acceptance\profilosistema;

use Tests\Support\AcceptanceTester;

final class TestLoginCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testLoginEseguito(AcceptanceTester $I): void
    {
        // 1 | open
        $I->amOnPage('/login');

        // 2-3 | click + type email
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');

        // 4-5 | click + type password
        $I->fillField(['name' => 'password'], 'veloce123');

        // 6 | click .invio
        $I->click('.invio');

        // 7 | verifica che sei sulla home dopo il login
        $I->seeCurrentUrlEquals('/');
    }
}
