<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HiddenFieldAntispamType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return "hidden_field_antispam";
    }

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['placeholder'] = $options['placeholder'];
        $choices = [];
        foreach ($options['choices'] as $k => $v) {
            $choices[] = ['label' => $k, 'value' => $v];
        }
        $view->vars['choices'] = $choices;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'empty_data' => null,
            'mapped' => false,
            'required' => false,
            'choices' => array_map(fn ($k) => substr(base64_encode(random_bytes(20 * $k)), 1, $k), range(rand(5, 10), rand(11, 21))),
            'placeholder' => "please choose a token",
        ]);
    }

}