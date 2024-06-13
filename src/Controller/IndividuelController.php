<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndividuelController extends AbstractController
{
    #[Route('/coaching-individuel', name: 'app_individuel')]
    public function index(): Response
    {
        return $this->render('individuel/index.html.twig', [
            'controller_name' => 'IndividuelController',
        ]);
    }
}
