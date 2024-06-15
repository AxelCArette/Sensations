<?php

namespace App\Controller\Admin;

use App\Entity\RedactionArticles;
use App\Entity\ArticleTag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class RedactionArticlesCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return RedactionArticles::class;
    }

    public function configureFields(string $pageName): iterable
    {

        $tags = $this->getTags();

        return [
            TextField::new('titre'),
            SlugField::new('slug')->setTargetFieldName('titre'),
            TextField::new('sousTitre'),
            TextEditorField::new('resumer'),
            TextEditorField::new('TexteDeLArticle'),
            BooleanField::new('published')->setLabel('Publié'),
            ImageField::new('image')
                ->setUploadDir('public/uploads/image')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
                ->setBasePath('uploads/image'),
            ChoiceField::new('tags')
                ->setChoices($tags)
                ->allowMultipleChoices()
                ->renderExpanded()
                ->setLabel('Tags'),
            DateTimeField::new('date')->setLabel('Date de publication')
        ];
    }

    private function getTags(): array
    {
       
        $tagRepository = $this->entityManager->getRepository(ArticleTag::class);
        $tags = $tagRepository->findAll();
        $choices = [];
        foreach ($tags as $tag) {
            $choices[$tag->getTagArticle()] = $tag->getTagArticle();
        }
        return $choices;
    }
}
