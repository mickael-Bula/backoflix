<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{ButtonType, SubmitType, TextType };

class VideoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', TextType::class, [
            'required' => true,
            'label' => 'entrez un titre de film ou de série',
            'attr' => [
                'placeholder' => 'saisissez un titre en anglais'
            ]
        ])
        // ->add('search', SubmitType::class)
        // ->add('save',   ButtonType::class)
        // ->add('cancel', ButtonType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
