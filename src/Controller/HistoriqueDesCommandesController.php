<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Entity\Formations;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueDesCommandesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/historique-des-commandes', name: 'app_historique_des_commandes')]
    public function historiqueDesCommandes(): Response
    {
        $commandes = $this->entityManager->getRepository(Commande::class)->findAll();

        $commandeDetails = $this->entityManager->getRepository(CommandeDetail::class)->findBy(['statut' => 1]);
        
        // Récupérer les noms des formations à partir de leurs IDs
        $nomsFormations = [];
        foreach ($commandeDetails as $commandeDetail) {
            $formationId = $commandeDetail->getFormation()->getId();
            $formation = $this->entityManager->getRepository(Formations::class)->find($formationId);
            $nomsFormations[$commandeDetail->getId()] = $formation ? $formation->getTitreDeLaFormation() : null;
        }

        return $this->render('accueil_compte/historiquedescommandes.html.twig', [
            'commandes' => $commandes,
            'commandeDetails' => $commandeDetails,
            'nomsFormations' => $nomsFormations,
        ]);
    }
}
