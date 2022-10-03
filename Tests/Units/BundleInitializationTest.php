<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Tests\Units;

use Nyholm\BundleTest\BaseBundleTestCase;

use MeteoConcept\HiddenFieldAntispamBundle\MeteoConceptHiddenFieldAntispamBundle;

class BundleInitializationTest extends BaseBundleTestCase
{
    protected function getBundleClass(): string
    {
        return MeteoConceptHiddenFieldAntispamBundle::class;
    }

    public function setUp(): void
    {
        $kernel = $this->createKernel();
        //$kernel->addConfigFile(__DIR__.'/test_config.yml');
        $this->bootKernel();
    }

    public function test_the_container_is_buildable()
    {
        $container = $this->getContainer();

        $this->assertTrue(null !== $container);
    }
}
