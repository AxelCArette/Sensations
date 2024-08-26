<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FormationsRepository;
use App\Repository\CommandeDetailRepository;
use App\Repository\FormationsGratuiteRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

class VenteDeformationsController extends AbstractController
{
    private $formationsRepository;
    private $commandeDetailRepository;
    private $formationsGratuiteRepository;

    public function __construct(
        FormationsRepository $formationsRepository, 
        CommandeDetailRepository $commandeDetailRepository,
        FormationsGratuiteRepository $formationsGratuiteRepository
    )
    {
        $this->formationsRepository = $formationsRepository;
        $this->commandeDetailRepository = $commandeDetailRepository;
        $this->formationsGratuiteRepository = $formationsGratuiteRepository;
    }

    #[Route('/compte/dlpdf/{id}', name: 'download_pdf01')]
    public function downloadPdf(int $id): BinaryFileResponse
    {
        $formation = $this->formationsGratuiteRepository->find($id);

        if (!$formation) {
            throw $this->createNotFoundException('La formation demandée n\'existe pas.');
        }

        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/uploads/pdf/' . $formation->getFichierpdfgratuit();
        
        return $this->file($pdfPath, $formation->getFichierpdfgratuit(), ResponseHeaderBag::DISPOSITION_INLINE);
    }

    #[Route('/formations', name: 'app_vente_deformations')]
    public function index(): Response
    {
        $user = $this->getUser();
        $formations = $this->formationsRepository->findAll();
        $formationsGratuites = $this->formationsGratuiteRepository->findAll();

        $commandeDetails = [];
        if ($user) {
            $details = $this->commandeDetailRepository->findByUser($user);
            foreach ($details as $detail) {
                $commandeDetails[$detail->getFormation()->getId()] = $detail;
            }
        }
        return $this->render('vente_deformations/index.html.twig', [
            'formations' => $formations,
            'formationsGratuites' => $formationsGratuites,
            'commandeDetails' => $commandeDetails,
        ]);
    }
}
