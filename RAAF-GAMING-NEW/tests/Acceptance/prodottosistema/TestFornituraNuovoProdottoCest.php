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

    public function testFornituraNuovoProdottoDigitaleOK(AcceptanceTester $I)
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
        $I->fillField('#prezzoProdotto', '19.99');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '04/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '55');
        $I->fillField('#pegi', '18');
        $I->fillField('#chiave', 'abcfgdtresfcnz');
        $I->selectOption('select[name="nomesfh"]', 'Epic Games');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Azione');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(5);

        $I->waitForElement('[name="successo"]', 20);
        $I->see('Prodotto inserito con successo!', '[name="successo"]');
    }

    public function testFornituraNuovoProdottoColoreConsoleNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#consoleRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'PS4');
        $I->fillField('#prezzoProdotto', '200');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '20');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#specifiche', 'vecchia');
        $I->fillField('#colore', 'asdfgvtdhrvjnyuhdrcbgftrydbvht');

        $I->click('#consoleForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('colore').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoConsoleOK(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#consoleRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'PS4');
        $I->fillField('#prezzoProdotto', '200');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '20');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#specifiche', 'vecchia');
        $I->fillField('#colore', 'nera');

        $I->click('#consoleForm button[type="submit"]');
        $I->wait(5);

        $I->waitForElement('[name="successo"]', 20);
        $I->see('Prodotto inserito con successo!', '[name="successo"]');
    }

    public function testFornituraNuovoProdottoSpecificaConsoleNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#consoleRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'PS4');
        $I->fillField('#prezzoProdotto', '200');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '20');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#specifiche', 'asdfghjkliopoiuytredfghjkkuiytfvgstrsbhyt');
        $I->fillField('#colore', 'nera');

        $I->click('#consoleForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('specifiche').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoAbbonamentoOK(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#abbonamentoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'abbonamento 1');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '2');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Nvidia');
        $I->fillField('#quantitaProdottoNew', '5');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#codiceAbb', 'asdfgtretrt');
        $I->fillField('#durAbb', '2');

        $I->click('#abbonamentoForm button[type="submit"]');
        $I->wait(5);

        $I->waitForElement('[name="successo"]', 20);
        $I->see('Prodotto inserito con successo!', '[name="successo"]');
    }

    public function testFornituraNuovoProdottoCodiceAbbonamentoNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#abbonamentoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'abbonamento 1');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '2');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Nvidia');
        $I->fillField('#quantitaProdottoNew', '5');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#codiceAbb', 'asdfghjkloiugyftrhbcetyvregcrcfd');
        $I->fillField('#durAbb', '2');

        $I->click('#abbonamentoForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('codiceAbb').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoDescrizioneDlcNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#dlcRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'dlc 1');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Nvidia');
        $I->fillField('#quantitaProdottoNew', '5');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#descDLC', 'dsgncfuidhcvidfuhnzcudyvadfjvc kfnhv dfijzvkhjdzf snv kjzdfsgv jhsnfcigersjygnc8uerhdfkjeryiudfhyn8erguerh8ersbyf8urh9ahfocibiuqeroeryicbfy98qercbfwcbffffffffffffffffffffffffffffffeariugfuadsihsxnuredctfgrdcgvbytresxdcgbhuy5f4rvtdcgvbhjyugtfrcgvh bjnyugbvh jdkoaifchiuerjqcfinaijerwuebgvwwwwwwwwwwwlntfcuybfvijhjndcjdcdcdnjdsnhjdchbdfreruiewioewuruhfedsxsxuhurftiugvnhdcxbgsgvdhefrtgiufdchsxsxdfgtyhjunhbtgvrdfhnybfgfrdgtf');
        $I->fillField('#dimDLC', '2');

        $I->click('#dlcForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('descDLC').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoDimensioneDlcNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#dlcRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'dlc 1');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Nvidia');
        $I->fillField('#quantitaProdottoNew', '5');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#descDLC', 'dlc');
        $I->executeJS("document.getElementById('dimDLC').removeAttribute('min')");
        $I->executeJS("document.getElementById('dimDLC').removeAttribute('max')");
        $I->executeJS("document.getElementById('dimDLC').value = '500'");

        $I->click('#dlcForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('dimDLC').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoDlcOK(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#dlcRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'dlc 1');
        $I->fillField('#prezzoProdotto', '10');
        $I->fillField('#scontoProdotto', '0');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Nvidia');
        $I->fillField('#quantitaProdottoNew', '5');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#descDLC', 'dlc');
        $I->fillField('#dimDLC', '10');

        $I->click('#dlcForm button[type="submit"]');
        $I->wait(5);

        $I->waitForElement('[name="successo"]', 20);
        $I->see('Prodotto inserito con successo!', '[name="successo"]');
    }

    public function testFornituraNuovoProdottoDurataAbbonamentoNO(AcceptanceTester $I)
    {
        $I->amOnPage('/admin');
        $I->fillField('#exampleInputEmail1', 'prodotto@admin.com');
        $I->fillField('#exampleInputPassword1', 'veloce123');
        $I->pressKey('#exampleInputPassword1', WebDriverKeys::ENTER);

        $I->waitForElement('#nuovoProdotto', 10);
        $I->checkOption('#nuovoProdotto');
        $I->wait(1);

        $I->checkOption('#abbonamentoRadio');
        $I->wait(1);

        $I->fillField('#nomeProdotto', 'abbonamento 1');
        $I->fillField('#prezzoProdotto', '20');
        $I->fillField('#scontoProdotto', '2');
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Nvidia');
        $I->fillField('#quantitaProdottoNew', '5');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#codiceAbb', 'asdfghjkiuy');
        $I->fillField('#durAbb', '50');

        $I->click('#abbonamentoForm button[type="submit"]');
        $I->wait(3);

        $I->dontSeeElement('[name="successo"]');
        $I->dontSeeElement('[name="errore"]');
    }

    public function testFornituraNuovoProdottoCopertinaNO(AcceptanceTester $I)
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
        $I->fillField('#uscitaProdotto', '04/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        // nessun attachFile per simulare copertina mancante

        $I->fillField('#dim', '10');
        $I->fillField('#pegi', '12');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Survival horror');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $borderColor = $I->executeJS("return document.getElementById('copertinaP').style.borderColor;");
        \PHPUnit\Framework\Assert::assertEquals('red', $borderColor);
    }

    public function testFornituraNuovoProdottoGestoreNO(AcceptanceTester $I)
    {
        $I->amOnPage('/homeProdotto');
        $I->seeInTitle('LOGIN-ADMIN');
    }

    public function testFornituraNuovoProdottoPEGINO(AcceptanceTester $I)
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
        $I->fillField('#uscitaProdotto', '03/01/2022');
        $I->selectOption('select[name="fornitoreP"]', 'Sony');
        $I->fillField('#quantitaProdottoNew', '1');

        $I->attachFile('#copertinaP', 'zelda.jpg');

        $I->fillField('#dim', '10');
        $I->fillField('#pegi', '50');
        $I->fillField('#ncd', '1');
        $I->selectOption('select[name="nomesfh"]', 'Ubisoft');
        $I->fillField('#limitata', '0');
        $I->selectOption('select[name="categoria"]', 'Survival horror');

        $I->click('#videogiocoForm button[type="submit"]');
        $I->wait(3);

        $I->dontSeeElement('[name="successo"]');
        $I->dontSeeElement('[name="errore"]');
    }
}
