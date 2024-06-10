<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoachingEntrepriseController extends AbstractController
{
    #[Route('/coaching-d-equipe', name: 'app_coaching_collectif')]
    public function index(): Response
    {
        return $this->render('coaching_collectif/index.html.twig');
    }
}
