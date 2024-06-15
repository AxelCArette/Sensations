<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Correction ici
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email*'
            ]) // Ajout du type EmailType
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques.',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe*',
                    'attr' => [
                        'placeholder' => 'Mot de passe',
                        'style' => 'background-color: transparent;',
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe*',
                    'attr' => [
                        'placeholder' => 'Votre mot de passe',
                        'style' => 'background-color: transparent;',
                    ]
                ]
            ]) 
            ->add('Civilite', ChoiceType::class, [
                'label' => 'Civilité*',
                'choices' => [
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame',
                ],
                'expanded' => true, 
                'multiple' => false, 
            ])
            ->add('agreeCGV', CheckboxType::class, [
                'label' => "J'accepte les conditions générales de vente ainsi que la politique de confidentialité de Sensations Coaching.",
                'mapped' => false,
                'required' => true,
            ])
            ->add('honeypot', HiddenType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('Nom', TextType::class, [
                'label' => 'Nom*'
            ])
            ->add('Prenom', TextType::class, [
                'label' => 'Prénom*'
            ]) 
            ->add('NumeroDeTelephone', TextType::class, [
                'label' => 'Numéro de téléphone*'
            ]) 
            ->add('Entreprise', TextType::class) 
            ->add('NumeroDeSiret', TextType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
