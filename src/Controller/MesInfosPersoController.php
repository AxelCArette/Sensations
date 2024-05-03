<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MesInfosPersoController extends AbstractController
{
    #[Route('compte/mes/infos/perso', name: 'app_mes_infos_perso')]
    public function index(): Response
    {
        return $this->render('accueil_compte/mesinfosperso.html.twig', [
            'controller_name' => 'MesInfosPersoController',
        ]);
    }
}
