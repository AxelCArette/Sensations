<?php

namespace App\Controller;

use App\Entity\CommandeDetail;
use App\Entity\Formations;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class FactureController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/facture', name: 'app_facture')]
    public function index(): Response
    {
        // Récupérer les détails de toutes les commandes depuis l'EntityManager
        $details = $this->entityManager->getRepository(CommandeDetail::class)->findAll();

        return $this->render('accueil_compte/facture.html.twig', [
            'details' => $details,
        ]);
    }

    #[Route('/compte/telecharger-facture/{commandeId}', name: 'app_telecharger_facture')]
    public function telechargerFacture($commandeId): Response
    {

        $commandeDetail = $this->entityManager->getRepository(CommandeDetail::class)->find($commandeId);


        // Générer la facture au format PDF
        $html = $this->renderView('accueil_compte/facture.html.twig', [
            'details' => $commandeDetail,
        ]);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Télécharger le PDF
        $nomFichier = 'facture.pdf';
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $nomFichier . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
