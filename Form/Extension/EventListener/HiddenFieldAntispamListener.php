<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Form\Extension\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class HiddenFieldAntispamListener implements EventSubscriberInterface
{
    private $fieldName;
    private $errorMessage;
    private $translator;
    private $translationDomain;

    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function __construct(string $fieldName, string $errorMessage, TranslatorInterface $translator = null, string $translationDomain = null)
    {
        $this->fieldName = $fieldName;
        $this->errorMessage = $errorMessage;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        if ($form->isRoot() && $form->getConfig()->getOption('compound')) {
            $data = $event->getData();

            $value = \is_string($data[$this->fieldName] ?? null) ? $data[$this->fieldName] : null;

            if (!empty($value)) {
                $errorMessage = $this->errorMessage;

                if (null !== $this->translator) {
                    $errorMessage = $this->translator->trans($errorMessage, [], $this->translationDomain);
                }

                $form->addError(new FormError($errorMessage, $errorMessage, [], null, ""));
            }

            if (\is_array($data)) {
                unset($data[$this->fieldName]);
                $event->setData($data);
            }
        }
    }
}
