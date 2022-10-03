<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Form\Extension\Type;

use MeteoConcept\HiddenFieldAntispamBundle\Form\Extension\EventListener\HiddenFieldAntispamListener;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * A type extension for base (also called "root") forms
 *
 * This type extension injects an antispam hidden field into every root forms (i.e. forms that are not fields or
 * fragments of another enclosing form). The field has an empty value and must come back empty as well, otherwise, it's
 * the sign that a bot has submitted the form instead of a real human visitor.
 */
class FormTypeHiddenFieldAntispamExtension extends AbstractTypeExtension
{
    /**
     * @var string|bool $enabled Whether the antispam mechanism is enabled
     */
    private string $enabled;

    /**
     * @var string $fieldName The name of the field to inject
     */
    private string $fieldName;

    /**
     * @var TranslatorInterface|null $translator A translator service for the error message
     */
    private ?TranslatorInterface $translator;

    /**
     * @var string|null $translationDomain The translation domain of the bundle
     */
    private ?string $translationDomain;

    /**
     * Constructs the type extension
     * @param bool $enabled Whether to enable the whole antispam mechanism
     * @param string $fieldName The name of the field to inject
     * @param TranslatorInterface|null $translator A translator service
     * @param string|null $translationDomain The bundle translation domain
     */
    public function __construct(bool $enabled, string $fieldName,
        ?TranslatorInterface $translator = null, ?string $translationDomain = null)
    {
        $this->enabled = $enabled;
        $this->fieldName = $fieldName;
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }

    /**
     * Adds the listener to the form to check the antispam field when the form is submitted
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['hidden_field_antispam_enabled']) {
            return;
        }

        $builder
            ->addEventSubscriber(new HiddenFieldAntispamListener(
                $options['hidden_field_antispam_field_name'],
                $options['hidden_field_antispam_message'],
                $this->translator,
                $this->translationDomain
            ))
        ;
    }

    /**
     * Adds the hidden antispam field to the root form view
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
            'hidden_field_antispam_field_name' => $this->fieldName,
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
