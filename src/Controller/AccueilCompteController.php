<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilCompteController extends AbstractController
{
    #[Route('/compte', name: 'app_accueil_compte')]
    public function index(): Response
    {
        return $this->render('accueil_compte/index.html.twig');
    }
}
