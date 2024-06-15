<?php

namespace App\Controller;

use App\Entity\RedactionArticles;
use App\Repository\RedactionArticlesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonArticleController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon/article/{id}', name: 'app_mon_article')]
    public function index(RedactionArticles $article, Request $request): Response
    {

        $titre = $article->getTitre();
        $sousTitre = $article->getSousTitre();
        $texteDeLArticle = $article->getTexteDeLArticle();

        $articleRepository = $this->entityManager->getRepository(RedactionArticles::class);
        $articles = $articleRepository->findAll();

        $tag = $request->query->get('tag');

        if ($tag) {
          
            $filteredArticles = [];
            foreach ($articles as $article) {
                if (in_array($tag, $article->getTags())) {
                    $filteredArticles[] = $article;
                }
            }
            $articles = $filteredArticles;
        }

        return $this->render('mon_article/MonArticle.html.twig', [
            'article' => $article,
            'resumes' => $articles, // Passer les articles filtrés à la vue
        ]);
    }
}
