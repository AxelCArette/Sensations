<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoachingSportifController extends AbstractController
{
    #[Route('/coaching-sportif', name: 'app_coaching_sportif')]
    public function index(): Response
    {
        return $this->render('coaching_sportif/index.html.twig');
    }
}
