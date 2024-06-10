<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MentionsLegaleController extends AbstractController
{
    #[Route('/mentions-legale', name: 'app_mentions_legale')]
    public function index(): Response
    {
        return $this->render('mentions_legale/index.html.twig');
    }
}
