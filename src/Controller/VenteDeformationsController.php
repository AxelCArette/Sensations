<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationsRepository;
use App\Entity\Formations; 

class VenteDeformationsController extends AbstractController
{
    private $formationsRepository;

    public function __construct(FormationsRepository $formationsRepository)
    {
        $this->formationsRepository = $formationsRepository;
    }

    #[Route('/vente-de-formations', name: 'app_vente_deformations')]
    public function index(): Response
    {
        $formations = $this->formationsRepository->findAll();

        return $this->render('vente_deformations/index.html.twig', [
            'formations' => $formations,
        ]);
    }
}
