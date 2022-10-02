<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Tests\Units;

use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;

use MeteoConcept\HiddenFieldAntispamBundle\DependencyInjection\MeteoConceptHiddenFieldAntispamExtension;

class MeteoConceptHiddenFieldAntispamExtensionTest extends AbstractExtensionTestCase
{
    protected function getContainerExtensions(): array
    {
        return array(
            new MeteoConceptHiddenFieldAntispamExtension()
        );
    }

    protected function getMinimalConfiguration(): array
    {
        return [
            'field_name' => 'field',
        ];
    }

    public function test_the form_extension_definition_is_passed_the_enabled_configuration_value()
    {
        $this->load();
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('meteo_concept_hidden_field_antispam.form_extension', 0, true);
    }

    public function test_the form_extension_definition_is_passed_the_field_name()
    {
        $this->load();
        $this->assertContainerBuilderHasServiceDefinitionWithArgument('meteo_concept_hidden_field_antispam.form_extension', 1, "field");
    }
}
