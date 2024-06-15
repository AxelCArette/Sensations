<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('Utilisateur', 'Utilisateur')->formatValue(function ($value, $entity) {
                return $value ? $value->getPrenom() : '-';
            }),
            MoneyField::new('prixtotal', 'Prix Total')->setCurrency('EUR')->hideOnForm(),
            ArrayField::new('commandeDetails', 'Détails de la commande')->hideOnForm()
              
                ->onlyOnDetail(),
        ];
    }
}
