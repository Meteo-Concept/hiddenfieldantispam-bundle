<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Form\Extension\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Injects itself into every form to hijack it and add an extra antispam field
 */
class HiddenFieldAntispamListener implements EventSubscriberInterface
{
    /**
     * @var string $fieldName The name of the field to add
     */
    private string $fieldName;
    /**
     * @var string $errorMessage The error message to display if the antispam triggers
     */
    private string $errorMessage;
    /**
     * @var TranslatorInterface|null A translator for the error message
     */
    private ?TranslatorInterface $translator;
    /**
     * @var string|null The translation domain to use
     */
    private ?string $translationDomain;

    /**
     * Constructs the listener, it's not (usually) a service so it doesn't benefit from dependency injection
     * @param string $fieldName The name of the field to inject into every form
     * @param string $errorMessage The error message to display in case of validation failure
     * @param TranslatorInterface|null $translator The translator service to translate the error message
     * @param string|null $translationDomain The bundle translation domain
     */
    public function __construct(string $fieldName, string $errorMessage, ?TranslatorInterface $translator = null, ?string $translationDomain = null)
    {
        $this->fieldName = $fieldName;
        $this->errorMessage = $errorMessage;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    /**
     * Verifies that the antispam field came back empty at submission
     *
     * This method adds a validation error to the form if the antispam hidden field is not empty.
     * @param FormEvent $event The submission event, containing the form to validate
     */
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

                $form->addError(new FormError($errorMessage, $errorMessage, [], null, "antispam security measure"));
            }

            if (\is_array($data)) {
                unset($data[$this->fieldName]);
                $event->setData($data);
            }
        }
    }
}
