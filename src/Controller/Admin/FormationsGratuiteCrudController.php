<?php

namespace App\Controller\Admin;

use App\Entity\FormationsGratuite;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class FormationsGratuiteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return FormationsGratuite::class;
    }


    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('titregratuit')->setLabel('Titre de la Formation');
        yield SlugField::new('slug')->setTargetFieldName('titregratuit');
        yield ImageField::new('imaegratuit')->setLabel("Image d'illustration")
            ->setUploadDir('public/uploads/image')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->setBasePath('uploads/image');
        yield ImageField::new('fichierpdfgratuit')
            ->setLabel('Fichier PDF')
            ->setUploadDir('public/uploads/pdf')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->setBasePath('uploads/pdf');
            yield TextField::new('VideoGratuite')->setLabel('Lien vidéo');
        yield TextEditorField::new('DescriptionGratuit')->setLabel('Description');
        yield IntegerField::new('nombre_de_pdf')->setLabel('Nombre de PDF');
        yield BooleanField::new('publier')->setLabel('Publié');
    }
}
