<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Form\Extension\Type;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\EventListener\HiddenFieldAntispamListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class FormTypeHiddenFieldAntispamExtension extends AbstractTypeExtension
{
    private string $enabled;

    private string $fieldName;

    private TranslatorInterface $translator;

    private string $translationDomain;

    public function __construct(string $fieldName,
        TranslatorInterface $translator = null, string $translationDomain = null)
    {
        $this->fieldName = $fieldName;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * Adds a CSRF field to the form when the CSRF protection is enabled.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['hidden_field_antispam_enabled']) {
            return;
        }

        $builder
            ->addEventSubscriber(new HiddenFieldAntispamListener(
                $options['hidden_field_antispam_field_name'],
                $translator,
                $translationDomain
            ))
        ;
    }

    /**
     * Adds a hidden field to the root form view.
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($options['hidden_field_antispam_enabled'] && !$view->parent && $options['compound']) {
            $factory = $form->getConfig()->getFormFactory();

            $hiddenFieldForm = $factory->createNamed($options['hidden_field_antispam_field_name'], HiddenType::class, NULL, [
                'block_prefix' => 'hidden_field_antispam',
                'mapped' => false,
            ]);

            $view->children[$options['hidden_field_antispam_field_name']] = $hiddenFieldForm->createView($view);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'hidden_field_antispam_enabled' => $this->enabled,
            'hidden_field_antispam_field_name' => $this->defaultFieldName,
            'hidden_field_antispam_message' => 'The hidden antispam field is filled-in. Please try to resubmit the form.',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [FormType::class];
    }
}
