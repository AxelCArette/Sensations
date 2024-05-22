<?php

namespace App\Controller;

use App\Entity\Commande;
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
        $utilisateur = $this->getUser();
        $commandes = $this->entityManager->getRepository(Commande::class)->findBy(['utilisateur' => $utilisateur]);

        $commandeDetailsGroupedByCommande = [];

        foreach ($commandes as $commande) {

            $details = $this->entityManager->getRepository(CommandeDetail::class)->findBy(['commande' => $commande]);
        
            $commandeDetailsGroupedByCommande[$commande->getId()] = $details;
        }

        return $this->render('accueil_compte/facture.html.twig', [
            'utilisateur' => $utilisateur,
            'commandeDetailsGroupedByCommande' => $commandeDetailsGroupedByCommande,
        ]);
    }

    #[Route('/compte/telecharger-facture/{commandeId}', name: 'app_telecharger_facture')]
    public function telechargerFacture($commandeId): Response
    {
        $utilisateur = $this->getUser();
        $now = new \DateTime();
        
        $image_path = 'asset/img/sensationlogo.png';
        $image_data = file_get_contents($image_path);
        $base64_image = base64_encode($image_data);

        $commandeDetails = $this->entityManager->getRepository(CommandeDetail::class)->findBy(['commande' => $commandeId]);
        $commande = $this->entityManager->getRepository(Commande::class)->find($commandeId);

        if (!$commandeDetails || !$commande) {
            throw $this->createNotFoundException('La commande ou les détails de la commande n\'existent pas.');
        }

        $adresse = $commandeDetails[0]->getAdresseUser();
        $referenceCommande = $commande->getReference();
        $formations = [];
        foreach ($commandeDetails as $detail) {
            $formations[] = $detail->getFormation();
        }

        $DateDeCreationCommande = $commande->getDateDeCreationCommande();
    
        $html = $this->renderView('accueil_compte/facture.html.twig', [
            'utilisateur' => $utilisateur,
            'adresse' => $adresse,
            'formations' => $formations,
            'referenceCommande' => $referenceCommande,
            'commande' => $commande,
            'DateDeCreationCommande' => $DateDeCreationCommande,
            'now' => $now,
            'base64_image' => $base64_image,
            'details' => $commandeDetails,
        ]);
    
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
    
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
    
        $nomFichier = 'facture_' . $referenceCommande . '.pdf';
        $response = new Response($dompdf->output());
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $nomFichier . '"');
        $response->headers->set('Cache-Control', 'max-age=0');
    
        return $response;
    }
}
