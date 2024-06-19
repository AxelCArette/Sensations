<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class CommandeCrudController extends AbstractCrudController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function configureActions(Actions $actions): Actions
    {
        return $actions -> add ('index','detail');
    }
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
            MoneyField::new('prixtotal', '=')->setCurrency('EUR'),
            ArrayField::new('commande', 'Détails de la commande')
           
                ->onlyOnDetail(),
        ];
    }
}
