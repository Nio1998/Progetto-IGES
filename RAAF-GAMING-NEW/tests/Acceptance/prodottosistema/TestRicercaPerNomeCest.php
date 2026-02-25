<?php

declare(strict_types=1);

namespace Tests\Acceptance\prodottosistema;

use Tests\Support\AcceptanceTester;
use Facebook\WebDriver\WebDriverKeys;

final class TestRicercaPerNomeCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testRicercaPerNomeCompletoOK(AcceptanceTester $I)
    {

        $I->amOnPage('/');
        $I->fillField(['name' => 'ricerca'], 'the last of us 2');
        $I->pressKey(['name' => 'ricerca'], WebDriverKeys::ENTER);
        $I->see('the last of us 2', '.card__title');
    }

    public function testRicercaPerNomeParziale(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField(['name' => 'ricerca'], 'ass');
        $I->pressKey(['name' => 'ricerca'], WebDriverKeys::ENTER);

        $I->waitForElement('.card__title', 10);
        $I->wait(2);

        $titles = $I->grabMultiple('.card__title');
        
        \PHPUnit\Framework\Assert::assertGreaterThan(0, count($titles));

        foreach ($titles as $title)
            \PHPUnit\Framework\Assert::assertStringContainsStringIgnoringCase('ass', $title);
    }

    public function testRicercaPerNomeNO(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->fillField(['name' => 'ricerca'], 'q');
        $I->pressKey(['name' => 'ricerca'], WebDriverKeys::ENTER);

        $I->wait(2);

        $I->dontSeeElement('.card__title');
    }
}
