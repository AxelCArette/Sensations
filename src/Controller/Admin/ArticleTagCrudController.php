<?php

namespace App\Controller\Admin;

use App\Entity\ArticleTag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ArticleTagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ArticleTag::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('tagArticle')
        ];
    }
}
