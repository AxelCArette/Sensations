<?php

namespace App\Controller\Admin;

use App\Entity\CommandeDetail;
use Doctrine\ORM\EntityManagerInterface;

use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommandeDetailCrudController extends AbstractCrudController
{

    private $entityManager;

    Public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return CommandeDetail::class;
    }

    public function configureActions(Actions $actions): Actions
    {
     return $actions
        ->add('index', 'detail');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('commande.utilisateur.nomComplet', 'Client'),
            ChoiceField::new('statut','Statut de la commande')->setChoices([
                'Commande en cours' => 0,
                'Commande payée' => 1,
            ]),
            TextField::new('commande.Reference', 'Commande'),
            MoneyField::new('prix', 'Prix')->setCurrency('EUR'),
        ];
    }
    
}
