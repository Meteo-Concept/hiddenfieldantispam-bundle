<?php

namespace MeteoConcept\HiddenFieldAntispamBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HiddenFieldAntispamType extends AbstractType
{
    public function getBlockPrefix(): string
    {
        return "hidden_field_antispam";
    }

    public function getParent(): ?string
    {
        // Take TextareaType as the parent because the hCaptcha widget kind of
        // takes up the same amount of space in a form (it's a rectangular box...)
        // so maybe this is a good default for layout?
        return ChoiceType::class;
    }

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