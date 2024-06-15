<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('Nom', TextType::class, [
            'label' => 'Votre nom',
        ])
            ->add('Prenom', TextType::class,[
                'label' => 'Votre prénom',
            ])
            ->add('Email', EmailType::class,[
                'label' => 'Votre Email',
            ])
            ->add('Sujet', TextType::class)
            ->add('VotreMessage', TextareaType::class)
            ->add('Envoyer', SubmitType::class,[
                'attr'=> [
                    'style' => 'color: white; background-color:#172d4b;', // Ajoute du style en ligne
            ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
