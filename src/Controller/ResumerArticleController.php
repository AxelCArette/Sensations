<?php

namespace App\Controller;

use App\Entity\RedactionArticles;
use App\Repository\ArticleTagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumerArticleController extends AbstractController
{
    private $entityManager;
    private $tagRepository;

    public function __construct(EntityManagerInterface $entityManager, ArticleTagRepository $tagRepository)
    {
        $this->entityManager = $entityManager;
        $this->tagRepository = $tagRepository;
    }

    #[Route('/resumer/article/{tag}', name: 'app_resumer_article', defaults: ['tag' => null])]
    public function index(?string $tag, Request $request): Response
    {
      
        $articleRepository = $this->entityManager->getRepository(RedactionArticles::class);
        $articles = $articleRepository->findAll();

 
        $tags = $this->tagRepository->findAll();

        if ($tag) {
            $filteredArticles = [];
            foreach ($articles as $article) {
                if (in_array($tag, $article->getTags())) {
                    $filteredArticles[] = $article;
                }
            }
            $articles = $filteredArticles;
        }
        
        return $this->render('mon_article/resumes.html.twig', [
            'resumes' => $articles,
            'tags' => $tags,
            'selectedTag' => $tag,
        ]);
    }
    #[Route('/api/resumer/article/{tag}', name: 'api_resumer_article', defaults: ['tag' => null], methods: ['GET'])]
public function apiIndex(?string $tag, Request $request): Response
{
    $articleRepository = $this->entityManager->getRepository(RedactionArticles::class);
    $articles = $articleRepository->findAll();

    if ($tag) {
        $filteredArticles = [];
        foreach ($articles as $article) {
            if (in_array($tag, $article->getTags())) {
                $filteredArticles[] = $article;
            }
        }
        $articles = $filteredArticles;
    }

    $responseArray = [];
    foreach ($articles as $article) {
        if ($article->getPublished()) {
            $responseArray[] = [
                'id' => $article->getId(),
                'titre' => $article->getTitre(),
                'sousTitre' => $article->getSousTitre(),
                'resumer' => $article->getResumer(),
                'image' => $article->getImage(),
            ];
        }
    }

    return $this->json($responseArray);
}

}
