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
        $utilisateur = $this->getUser();

        $commandes = $this->entityManager->getRepository(Commande::class)->findBy(['Utilisateur' => $utilisateur]);

        $commandeDetailsGroupedByCommande = [];
        $nomsFormations = [];

        foreach ($commandes as $commande) {
    
            $commandeDetails = $this->entityManager->getRepository(CommandeDetail::class)->findBy(['commande' => $commande, 'statut' => 1]);

            foreach ($commandeDetails as $commandeDetail) {
                $commandeId = $commande->getId();
                $commandeDetailsGroupedByCommande[$commandeId][] = $commandeDetail;

                $formationId = $commandeDetail->getFormation()->getId();
                $formation = $this->entityManager->getRepository(Formations::class)->find($formationId);
                $nomsFormations[$commandeDetail->getId()] = $formation ? $formation->getTitreDeLaFormation() : null;
            }
        }

        return $this->render('accueil_compte/historiquedescommandes.html.twig', [
            'commandes' => $commandes,
            'commandeDetailsGroupedByCommande' => $commandeDetailsGroupedByCommande,
            'nomsFormations' => $nomsFormations,
        ]);
    }
}
