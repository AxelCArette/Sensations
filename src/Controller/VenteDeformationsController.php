<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationsRepository;
use App\Repository\CommandeDetailRepository;

class VenteDeformationsController extends AbstractController
{
    private $formationsRepository;
    private $commandeDetailRepository;

    public function __construct(FormationsRepository $formationsRepository, CommandeDetailRepository $commandeDetailRepository)
    {
        $this->formationsRepository = $formationsRepository;
        $this->commandeDetailRepository = $commandeDetailRepository;
    }

    #[Route('/vente-de-formations', name: 'app_vente_deformations')]
    public function index(): Response
    {
        $user = $this->getUser();
        $formations = $this->formationsRepository->findAll();

        $commandeDetails = [];
        if ($user) {
            $details = $this->commandeDetailRepository->findByUser($user);
            foreach ($details as $detail) {
                $commandeDetails[$detail->getFormation()->getId()] = $detail;
            }
        }

        return $this->render('vente_deformations/index.html.twig', [
            'formations' => $formations,
            'commandeDetails' => $commandeDetails,
        ]);
    }
    
}
