<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Tests\Units;

use MeteoConcept\HiddenFieldAntispamBundle\Form\Extension\EventListener\HiddenFieldAntispamListener;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormConfigInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

class HiddenFieldAntispamListenerTest extends \PHPUnit\Framework\TestCase
{
    public function testTheListenerThrowsErrorIfAntispamFieldIsFilledIn()
    {
        $listener = new HiddenFieldAntispamListener("test_field", "not good!");

        $event = $this->createMock(FormEvent::class);
        $form = $this->createMock(FormInterface::class);
        $event->expects($this->any())
              ->method("getForm")
              ->willReturn($form);

        $event->expects($this->any())
            ->method("getData")
            ->willReturn(['test_field' => 'filled-in']);

        $form->expects($this->once())
            ->method("isRoot")
            ->willReturn(true);

        $config = $this->createMock(FormConfigInterface::class);
        $form->expects($this->once())
            ->method("getConfig")
            ->willReturn($config);

        $config->expects($this->once())
            ->method("getOption")
            ->with("compound", null)
            ->willReturn(true);

        $form->expects($this->once())
            ->method("addError")
            ->with(new FormError("not good!", "not good!", [], null, "antispam security measure"));

        $listener->preSubmit($event);
    }
    public function testTheListenerDoesNotThrowErrorIfAntispamFieldIsNotFilledIn()
    {
        $listener = new HiddenFieldAntispamListener("test_field", "not good!");

        $event = $this->createMock(FormEvent::class);
        $form = $this->createMock(FormInterface::class);
        $event->expects($this->any())
            ->method("getForm")
            ->willReturn($form);

        $event->expects($this->any())
            ->method("getData")
            ->willReturn(['test_field' => '']);

        $form->expects($this->once())
            ->method("isRoot")
            ->willReturn(true);

        $config = $this->createMock(FormConfigInterface::class);
        $form->expects($this->once())
            ->method("getConfig")
            ->willReturn($config);

        $config->expects($this->once())
            ->method("getOption")
            ->with("compound", null)
            ->willReturn(true);

        $form->expects($this->never())
            ->method("addError")
            ->with(new FormError("not good!", "not good!", [], null, "antispam security measure"));

        $listener->preSubmit($event);
    }
}