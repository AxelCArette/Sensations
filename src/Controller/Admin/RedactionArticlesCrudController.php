<?php

namespace App\Controller\Admin;

use App\Entity\RedactionArticles;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class RedactionArticlesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RedactionArticles::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
           TextField::new('Titre'),
           SlugField::new('slug')->setTargetFieldName('Titre'),
           TextField::new('sousTitre'),
           TextField::new('tag'),
           TextField::new('resumer'),
           TextField::new('TexteDeLArticle'),
           BooleanField::new('published')->setLabel('Publié'),
           ImageField::new('image')
            ->setUploadDir('public/uploads/image')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false)
            ->setBasePath('uploads/image'),
        ];
    }
    
}
