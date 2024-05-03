<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Classe\Cart;
use App\Repository\FormationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DetailCommandeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_detail_commande')]
    public function index(Cart $cart, Request $request, FormationsRepository $formationsRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
    
        $commande = new Commande();
        $commande->setUtilisateur($this->getUser());
     
    
        $reference = date('dmYHis') . '-' . uniqid();
        $commande->setReference($reference);
    
        $formationsIdsDansPanier = $cart->get();
    
        $formationsDansPanier = [];
        foreach ($formationsIdsDansPanier as $formationId => $quantity) {
            $formation = $formationsRepository->find($formationId);
            if ($formation) {
                $formationsDansPanier[] = $formation;
            }
        }
    
        return $this->render('detail_commande/index.html.twig', [
            'formations' => $formationsDansPanier,
        ]);
    }

    #[Route('/paiement', name: 'app_paiement')]
    public function paiement(Cart $cart, Request $request, FormationsRepository $formationsRepository): Response
   {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $this->addFlash('success', 'Votre commande a été passée avec succès.');
        }

        $formationsIdsDansPanier = $cart->get();
        $formationsDansPanier = [];

        foreach ($formationsIdsDansPanier as $formationId => $quantity) {
            $formation = $formationsRepository->find($formationId);
            if ($formation) {
                $formationsDansPanier[] = $formation;
            }
        }

        $commandeDetails = [];
        foreach ($formationsDansPanier as $formation) {
            $commandeDetail = new CommandeDetail();
            $commandeDetail->setCommande($this->getUser()->getCommandeActuelle());
            $commandeDetail->setFormation($formation);
            
            $prixFormation = $formation->getPrix();
            if ($prixFormation !== null) {
                $commandeDetail->setPrix($prixFormation);
                $commandeDetail->setPrixtotal($prixFormation);
            }

            $commandeDetail->setStatut(0);
            $adresseUtilisateur = $this->getUser()->getAdresses()->first();
            $adresseUser = $adresseUtilisateur ? $adresseUtilisateur->getAdresseComplete() : '';
            $commandeDetail->setAdresseUser($adresseUser);

            $commandeDetails[] = $commandeDetail;
        }

        foreach ($commandeDetails as $commandeDetail) {
            $this->entityManager->persist($commandeDetail);
        }
        $this->entityManager->flush();

        $cart->remove();

        return new Response('C ok');
    }
}

