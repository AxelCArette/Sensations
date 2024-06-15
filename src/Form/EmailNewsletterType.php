<?php

namespace App\Form;

use App\Entity\EmailNewsletter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EmailNewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('emailpournewletter', EmailType::class, [
                'label' => 'Adresse email',
                'required' => true,
            ])
            ->add('entreprise', CheckboxType::class, [
                'label' => 'Entreprises',
                'required' => false,
            ])
            ->add('sportifDeHautNiveau', CheckboxType::class, [
                'label' => 'Sportifs de haut-niveau',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Rejoindre la newsletter',
                'attr'=> [
                    'style' => 'color: white; background-color:#172d4b;', // Ajoute du style en ligne
            ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmailNewsletter::class,
        ]);
    }
}
