<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Tests\Functional;

use Symfony\Component\Panther\PantherTestCase;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class SubmitFormWebTest extends PantherTestCase
{
    public function testEverythingCompilesAndAppRuns()
    {
        $client = self::bootKernel();
        $this->assertNotNull($client->getContainer());
    }

    public function testFormIsBuiltAndDisplayed()
    {
        $client = self::createPantherClient([
            'webServerDir' => __DIR__.'/public/',
            'router' => 'index.php',
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/form');
        $client->waitFor("form");
        $this->assertSelectorExists('input[name="form[meteo_concept_sentinel]"]');
    }

    public function testSuccessfulEmptyValueIsHandledCorrectly()
    {
        $client = self::createPantherClient([
            'webServerDir' => __DIR__.'/public/',
            'router' => 'index.php',
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/form');

        $client->switchTo()->defaultContent();
        $form = $crawler->selectButton('form_submit')->form();
        $client->submit($form, [
            'form[witness]' => 'test',
        ]);

        // Check that we have been redirected to the 'ok' webpage
        $this->assertSelectorWillContain('#status', 'ok');
        $this->assertSelectorWillContain('#witness', 'test');
    }

    public function testErroneouslyFilledInValueIsHandledCorrectly()
    {
        $client = self::createPantherClient([
            'webServerDir' => __DIR__.'/public/',
            'router' => 'index.php',
            'browser' => static::FIREFOX,
        ]);

        $crawler = $client->request('GET', '/form');

        $client->switchTo()->defaultContent();
        $form = $crawler->selectButton('form_submit')->form();
        $client->executeScript("document.querySelector('input[name=\"form[meteo_concept_sentinel]\"]').value = 'Should not be there!';");
        $client->submit($form, [
            'form[witness]' => 'test',
        ]);

        // Check that we have been redirected to the form instead of the 'ok' webpage
        $this->assertSelectorNotExists('#status');
        $this->assertSelectorExists('form');
    }
}

