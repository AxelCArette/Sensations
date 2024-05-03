<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UtilisateurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('Civilite'),
            TextField::new('Nom'),
            TextField::new('Prenom'),
            IntegerField::new('NumeroDeTelephone'),
            ArrayField::new('roles', 'Roles'),
            TextField::new('email'),
            AssociationField::new('adresses', 'Adresse(s)')
                ->hideOnForm()
                ->formatValue(function ($value, $entity) {
                    return $entity->getAdresses()->first()->getVille();
                }),
        ];
    }
}
