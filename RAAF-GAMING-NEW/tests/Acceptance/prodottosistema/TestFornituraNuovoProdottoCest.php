<?php

declare(strict_types=1);

namespace Tests\Acceptance\prodottosistema;

use Tests\Support\AcceptanceTester;
use Facebook\WebDriver\WebDriverKeys;

final class TestFornituraNuovoProdottoCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test function.
    }

    public function testFornituraNuovoVideogiocoFisicoOK(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        // Seleziona "Nuovo prodotto"
        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        // Seleziona "Videogioco fisico"
        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        // Compila i campi comuni
        $I->fillField('#nomeProdotto', 'Spiderman');
        $I->fillField('#prezzoProdotto', '30');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '27/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '10');

        // Upload immagine
        $I->attachFile('#copertinaP', 'zelda.jpg');

        // Campi specifici videogioco fisico
        $I->fillField('#dim', '50');
        $I->fillField('#pegi', '18');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '1');
        $I->selectOption('select[name="categoria"]', 'Azione');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(5);
        $I->waitForElement('[name="successo"]', 20);
        $I->see('Prodotto inserito con successo!', '[name="successo"]');
    }

    public function testFornituraNuovoVideogiocoFisicoMagazzinoNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'Gioco');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '28/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '10000');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '20');
        $I->fillField('#pegi', '18');
        $I->fillField('#ncd', '2');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Azione');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(5);

        $I->waitForElement('[name="errore"]', 20);
        $I->see('Capienza non disponibile', '[name="errore"]');
    }

    public function testFornituraNuovoProdottoNomeNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'PRODJDNCSINACNCIJDSAFICURNVUTRGSIUGTRSINTRDFKLVNOIPGFRNDSJVNTRGFNPUOIRDFKLVFOIPUGTRKGVOIUPJTFRGVTRDSIUNTRKLPOIKCNVTRDFHSMOIAKCFUEKLDBUOIERWMNCTH8UFFFFFFFFFFFDVXCYRRRNU IJFKDBSAIOFXYHCZNRFV');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '29/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '2');
        $I->fillField('#pegi', '12');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('nomeProdotto').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoPrezzoNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'Prova');
        $I->fillField('#prezzoProdotto', '-10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '18/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '10');
        $I->fillField('#pegi', '8');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Survival horror');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('prezzoProdotto').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoDimensioneVideogiocoNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'Prova');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '1');
        $I->fillField('#uscitaProdotto', '27/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->executeJS("document.getElementById('dim').removeAttribute('min')");
        $I->executeJS("document.getElementById('dim').value = '-10'");
        $I->fillField('#pegi', '18');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Survival horror');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $I->dontSeeElement('[name="successo"]');
        $I->dontSeeElement('[name="errore"]');

        $borderColor = $I->executeJS("return document.getElementById('dim').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoNCDVideogiocoNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'Prova');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '28/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '10');
        $I->fillField('#pegi', '18');
        $I->fillField('#ncd', '-1');

        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Survival horror');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $I->dontSeeElement('[name="successo"]');
        $I->dontSeeElement('[name="errore"]');
    }

    public function testFornituraNuovoProdottoQuantitaNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'Prova');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '27/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '-20');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '10');
        $I->fillField('#pegi', '18');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Survival horror');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $I->dontSeeElement('[name="successo"]');
        $I->dontSeeElement('[name="errore"]');
    }

    public function testFornituraNuovoProdottoScontoNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'Prova');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '-20');
        $I->fillField('#uscitaProdotto', '29/12/2021');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '10');
        $I->fillField('#pegi', '18');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Survival horror');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $I->dontSeeElement('[name="successo"]');
        $I->dontSeeElement('[name="errore"]');
    }

    public function testFornituraNuovoProdottoVKEYNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#videogiocoRadio2');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'Detroit');
        $I->fillField('#prezzoProdotto', '19');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '04/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '55');
        $I->fillField('#pegi', '18');
        $I->fillField('#chiave', 'asdfghjkloiuytredfgc');
        $I->selectOption('select[name="nomesfh"]', 'Epic Games');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Azione');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('chiave').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

}
