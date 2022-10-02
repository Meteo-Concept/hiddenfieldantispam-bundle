<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Form;

use Symfony\Component\Form\AbstractExtension;
use Symfony\Contracts\Translation\TranslatorInterface;

use MeteoConcept\HiddenFieldAntispamBundle\Validator\Constraints\IsEmptyHiddenAntispamField;

/**
 * This extension protects forms by adding an empty hidden field that must come
 * back empty (dumb bots usually fill them in)
 */
class HiddenFieldAntispamExtension extends AbstractExtension
{
    private bool $enabled;

    private string $fieldName;

    private TranslatorInterface $translator;

    private string $translationDomain;

    public function __construct(bool $enabled = true, string $fieldName = 'meteo_concept_sentinel',
        TranslatorInterface $translator = null, string $translationDomain = null)
    {
        $this->enabled = $enabled;
        $this->fieldName = $fieldName;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadTypeExtensions()
    {
        if ($this->enabled) {
            return [
                new Type\FormTypeHiddenAntispamFieldExtension(
                    $this->fieldName,
                    $this->translator,
                    $this->translationDomain
                ),
            ];
        } else {
            return [];
        }
    }
}
