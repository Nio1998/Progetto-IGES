<?php

declare(strict_types=1);

namespace Tests\Acceptance\prodottosistema;

use Tests\Support\AcceptanceTester;

final class RecensioneTestCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function recensioneSenzaLoggare(AcceptanceTester $I)
    {
        // 7 | open 
        $I->amOnPage('/');

        // 8 | apertura prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 9-10| selezione stella
        $I->wait(5);
        $I->scrollTo('.btn-dark');
        $I->click('#stella9');
        // 11 | inserimento commento
        $I->fillField('#commento', 'bello');
        // 12 | invio recensione
        $I->click('.btn-dark');

        // 13 | assertAlert
        $I->wait(5);
        $I->seeInPopup('Effettua l\'accesso per recensire!');
        $I->acceptPopup();
    }

    public function recensioneEffetuataConSuccesso(AcceptanceTester $I)
    {
        // 1 | open | servletloginfirst
        $I->amOnPage('/login');

        // 3-6 | login
        $I->click(['name' => 'email']);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');

        $I->click(['name' => 'password']);
        $I->fillField(['name' => 'password'], 'veloce123');

        // 7 | submit login
        $I->click('.invio');

        // 8 | apertura prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 9-10| selezione stella
        $I->wait(5);
        $I->scrollTo('.btn-dark');
        $I->click('#stella9');
        // 11 | inserimento commento
        $I->fillField('#commento', 'bello');
        // 12 | invio recensione
        $I->click('.btn-dark');

        // 13 | assertAlert
        $I->wait(5);
        $I->seeInPopup('Recensione effettuata con voto 9');
        $I->acceptPopup();
    }

    public function recensioneGiaEffettuata(AcceptanceTester $I)
    {
        // 1 | open | servletloginfirst
        $I->amOnPage('/login');

        // 3-6 | login
        $I->click(['name' => 'email']);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');

        $I->click(['name' => 'password']);
        $I->fillField(['name' => 'password'], 'veloce123');

        // 7 | submit login
        $I->click('.invio');

        // 8 | apertura prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 9-10| selezione stella
        $I->wait(5);
        $I->scrollTo('.btn-dark');
        $I->click('#stella9');
        // 11 | inserimento commento
        $I->fillField('#commento', 'ciao');
        // 12 | invio recensione
        $I->click('.btn-dark');

        // 13 | assertAlert
        $I->wait(5);
        $I->seeInPopup('Hai già recensito questo prodotto');
        $I->acceptPopup();
    }

    public function recensioneSenzaVoto(AcceptanceTester $I)
    {
        // 1 | open | servletloginfirst
        $I->amOnPage('/login');

        // 3-6 | login
        $I->click(['name' => 'email']);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');

        $I->click(['name' => 'password']);
        $I->fillField(['name' => 'password'], 'veloce123');

        // 7 | submit login
        $I->click('.invio');

        // 8 | apertura prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 9-10| selezione stella
        $I->wait(5);
        $I->scrollTo('.btn-dark');
        // 11 | inserimento commento
        $I->fillField('#commento', 'ciao');
        // 12 | invio recensione
        $I->click('.btn-dark');

        // 13 | assertAlert
        $I->wait(5);
        $I->seeInPopup('Non hai inserito il voto');
        $I->acceptPopup();
    }

    public function recensioneSenzaCommento(AcceptanceTester $I)
    {
        // 1 | open | servletloginfirst
        $I->amOnPage('/login');

        // 3-6 | login
        $I->click(['name' => 'email']);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');

        $I->click(['name' => 'password']);
        $I->fillField(['name' => 'password'], 'veloce123');

        // 7 | submit login
        $I->click('.invio');

        // 8 | apertura prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 9-10| selezione stella
        $I->wait(5);
        $I->scrollTo('.btn-dark');
        $I->click('#stella9');

        // 12 | invio recensione
        $I->click('.btn-dark');

        // 13 | assertAlert
        $I->wait(5);
        $I->seeInPopup('Commento non inserito');
        $I->acceptPopup();
    }

    public function recensioneSenzaVotoECommento(AcceptanceTester $I)
    {
        // 1 | open | servletloginfirst
        $I->amOnPage('/login');

        // 3-6 | login
        $I->click(['name' => 'email']);
        $I->fillField(['name' => 'email'], 'f.peluso25@gmail.com');

        $I->click(['name' => 'password']);
        $I->fillField(['name' => 'password'], 'veloce123');

        // 7 | submit login
        $I->click('.invio');

        // 8 | apertura prodotto
        $I->click('.row:nth-child(1) li:nth-child(1) span:nth-child(1)');

        // 9-11| selezione
        $I->wait(5);
        $I->scrollTo('#commento');

        // 12 | invio recensione
        $I->click('.btn-dark');

        // 13 | assertAlert
        $I->wait(5);
        $I->seeInPopup('Non hai inserito il voto');
        $I->acceptPopup();
    }
}
