<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaiementreussiController extends AbstractController
{
    #[Route('/paiementreussi', name: 'app_paiementreussi')]
    public function index(): Response
    {
        return $this->render('paiementreussi/index.html.twig', [
            'controller_name' => 'PaiementreussiController',
        ]);
    }
}
