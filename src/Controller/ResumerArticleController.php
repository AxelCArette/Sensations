<?php

namespace App\Controller;

use App\Entity\RedactionArticles;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResumerArticleController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/resumer/article', name: 'app_resumer_article')]
    public function index(): Response
    {
        $repository = $this->entityManager->getRepository(RedactionArticles::class);
        $articles = $repository->findAll();
    
        $resumes = [];
        foreach ($articles as $article) {
            $resumes[] = [
                'id' => $article->getId(),
                'titre' => $article->getTitre(),
                'image'=>$article->getImage(),
                'sousTitre' => $article->getSousTitre(),
                'resumer' => $article->getResumer(),
                'published' => $article->getPublished(),
            ];
        }
    
        return $this->render('mon_article/resumes.html.twig', [
            'resumes' => $resumes,
        ]);
    }
    
}
