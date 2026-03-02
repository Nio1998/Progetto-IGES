<?php

declare(strict_types=1);

namespace Tests\Acceptance\profilosistema;

use Tests\Support\AcceptanceTester;

final class TestLoginCest
{

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

    public function testLoginFallitoPassword(AcceptanceTester $I): void
    {
        // 1 | vai alla pagina login
        $I->amOnPage('/login');

        // 2 | inserisci email corretta
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');

        // 3 | inserisci password errata
        $I->fillField(['name' => 'password'], 'veloce1234');

        // 4 | click login
        $I->click('.invio');

        // 5 | verifica che sei ancora su login
        $I->seeCurrentUrlEquals('/login');

        // 6 | verifica messaggio di errore
        $I->see('Email/Password errata!');
    }

    public function testLoginFallito(AcceptanceTester $I): void
    {
        // 1 | vai alla pagina login
        $I->amOnPage('/login');

        // 2 | inserisci email corretta
        $I->fillField(['name' => 'email'], 'abc@gmail.com');

        // 3 | inserisci password errata
        $I->fillField(['name' => 'password'], 'abcdesfg123');

        // 4 | click login
        $I->click('.invio');

        // 5 | verifica che sei ancora su login
        $I->seeCurrentUrlEquals('/login');

        // 6 | verifica messaggio di errore
        $I->see('Email/Password errata!');
    }
}
