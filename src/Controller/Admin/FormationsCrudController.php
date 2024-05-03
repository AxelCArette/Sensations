<?php

namespace App\Controller\Admin;

use App\Entity\Formations;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;

class FormationsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Formations::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('TitreDeLaFormation')->setLabel('Titre de la Formation');
        yield SlugField::new('slug')->setTargetFieldName('TitreDeLaFormation');
        yield ImageField::new('fichier_pdf')
            ->setLabel('Fichier PDF')
            ->setUploadDir('public/uploads/pdf')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->setBasePath('uploads/pdf');
        yield TextField::new('video')->setLabel('Lien vidéo');
        yield TextField::new('description')->setLabel('Description');
        yield IntegerField::new('dureeEnSecondes')->setLabel('Durée en Secondes');
        yield IntegerField::new('nombreDePDF')->setLabel('Nombre de PDF');
        yield MoneyField::new('prix')->setLabel('Prix')->setCurrency('EUR');
        yield BooleanField::new('publier')->setLabel('Publié');
    }
}
