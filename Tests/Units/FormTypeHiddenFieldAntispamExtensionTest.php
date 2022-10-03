<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Tests\Units;

use MeteoConcept\HiddenFieldAntispamBundle\Form\Extension\Type\FormTypeHiddenFieldAntispamExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class FormTypeHiddenFieldAntispamExtensionTest extends \Symfony\Component\Form\Test\TypeTestCase
{
    protected function getTypeExtensions(): array
    {
        return [
            new FormTypeHiddenFieldAntispamExtension(true, "test_field_name"),
        ];
    }

    public function testFormIsBuiltCorrectlyWithTheAdditionalOptions()
    {
        $form = $this->factory->createBuilder(FormType::class, ['witness' => 'ok'])->getForm();

        $form->submit(['witness' => 'ok']);

        $this->assertTrue($form->isSynchronized());

        $this->assertArrayHasKey('hidden_field_antispam_enabled', $form->getRoot()->getConfig()->getOptions());
        $this->assertEquals("1", $form->getRoot()->getConfig()->getOption('hidden_field_antispam_enabled'));
        $this->assertArrayHasKey('hidden_field_antispam_field_name', $form->getRoot()->getConfig()->getOptions());
        $this->assertEquals("test_field_name", $form->getRoot()->getConfig()->getOption('hidden_field_antispam_field_name'));
    }
}