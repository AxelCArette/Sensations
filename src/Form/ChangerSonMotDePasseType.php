<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType as TypeTextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangerSonMotDePasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom',TypeTextType::class, [
            'disabled'=>true,
            'label'=>'Mon nom de famille'
        ])
        ->add('Prenom',TypeTextType::class,[
            'disabled'=>true,
            'label'=>'Mon Prénom'
        ])
            ->add('old_password', PasswordType::class,[
                'label'=>'Mon mot de passe actuel',
                'mapped'=>false
                ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped'=>false,
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
                'label'=> 'Changez votre mot de passe'
            ])
            ;
       
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
