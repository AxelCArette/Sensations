<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('new_password', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques.',
            'required' => true,
            'first_options' => [
                'label' => 'Votre nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Votre mot de passe.',
                    'style' => 'background-color: transparent; border: none;',
                ]
            ],
            'second_options' => [
                'label' => 'Confirmez votre nouveau mot de passe',
                'attr' => [
                    'placeholder' => 'Confirmez votre mot de passe.',
                    'style' => 'background-color: transparent; border: none;',
                ]
            ]
        ])
        ->add('submit', SubmitType::class, [
            'label'=> 'Mettre à jour mon mot de passe',
            'attr'=> [
                'class'=>'btn btn-outline-info btn-block'
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
