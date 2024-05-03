<?php

namespace App\Controller;

use App\Entity\CommandeDetail;
use App\Repository\CommandeDetailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ConsulterCesFormationsController extends AbstractController
{
    #[Route('compte/consulter-ces-formations', name: 'app_consulter_ces_formations')]
    public function index(CommandeDetailRepository $commandeDetailRepository): Response
    {
        $commandeDetails = $commandeDetailRepository->findAll();

        return $this->render('accueil_compte/consultercesformations.html.twig', [
            'commandeDetails' => $commandeDetails
        ]);
    }

    #[Route('compte/download-pdf/{id}', name: 'download_pdf')]
    public function downloadPdf(CommandeDetail $commandeDetail = null): Response
    {
    
        if ($commandeDetail === null) {
            $errorMessage = 'Cette formation n existe pas';
            return $this->render('error403.html.twig', [
                'errorMessage' => $errorMessage
            ]);
        }

        $user = $this->getUser();

        if ($commandeDetail->getStatut() !== 1 || $commandeDetail->getCommande()->getUtilisateur() !== $user) {
            $errorMessage = 'Vous n\'êtes pas autorisé à télécharger ce PDF.';
            return $this->render('error403.html.twig', [
                'errorMessage' => $errorMessage
            ]);
        }

        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/uploads/pdf/' . $commandeDetail->getFormation()->getFichierPDF();

        if (!file_exists($pdfPath)) {
            throw $this->createNotFoundException('Le fichier PDF demandé n\'existe pas.');
        }

        $response = new BinaryFileResponse($pdfPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }
}
