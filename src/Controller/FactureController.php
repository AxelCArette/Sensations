<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
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
        
        if (!$utilisateur) {
            return $this->redirectToRoute('app_login');
        }

        $commandes = $this->entityManager->getRepository(Commande::class)->findBy(['Utilisateur' => $utilisateur]);

        $commandeDetailsGroupedByCommande = [];

        foreach ($commandes as $commande) {
            $details = $this->entityManager->getRepository(CommandeDetail::class)->findBy(['Commande' => $commande]);
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
        $commande = $this->entityManager->getRepository(Commande::class)->find($commandeId);

        if (!$commande || $commande->getUtilisateur() !== $utilisateur) {
            throw $this->createNotFoundException('La commande n\'existe pas ou vous n\'êtes pas autorisé à la consulter.');
        }

        $commandeDetails = $this->entityManager->getRepository(commandeDetail::class)->findBy(['commande' => $commande]);
        $commandeDetails=$this->entityManager->getRepository(commandeDetail::class)->findByStatut('1');

        if (!$commandeDetails) {
            throw $this->createNotFoundException('Les détails de la commande n\'existent pas.');
        }

        $now = new \DateTime();
        $image_path = 'asset/img/sensationlogo.png';
        $image_data = file_get_contents($image_path);
        $base64_image = base64_encode($image_data);

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
