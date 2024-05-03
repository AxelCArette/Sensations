<?php

namespace App\Controller;

use App\Entity\RedactionArticles;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MonArticleController extends AbstractController
{
    #[Route('/mon/article/{id}', name: 'app_mon_article')]
    public function index(RedactionArticles $article): Response
    {
        
        $titre = $article->getTitre();
        $sousTitre = $article->getSousTitre();
        $texteDeLArticle = $article->getTexteDeLArticle();

        return $this->render('mon_article/MonArticle.html.twig', [
            'article' => $article, 
        ]);
    }
}
