<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoachingEntrepriseController extends AbstractController
{
    #[Route('/coaching-entreprise', name: 'app_coaching_entreprise')]
    public function index(): Response
    {
        return $this->render('coaching_entreprise/index.html.twig');
    }
}
