<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class QuiSuisJeController extends AbstractController
{
    #[Route('/qui-suis-je', name: 'app_qui_suis_je')]
    public function index(): Response
    {
        return $this->render('qui_suis_je/index.html.twig');
    }
}
