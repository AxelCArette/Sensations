<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Classe\Cart;
use App\Repository\CommandeDetailRepository;
use App\Repository\FormationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Stripe;
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
        $nomsFormations = []; // Défini la variable ici

        foreach ($formationsIdsDansPanier as $formationId => $quantity) {
            $formation = $formationsRepository->find($formationId);
            if ($formation) {
                $formationsDansPanier[] = $formation;
                $nomsFormations[] = $formation->getTitreDeLaFormation();
            }
        }
        $nomsFormationsStr = implode(', ', $nomsFormations);

        return $this->render('detail_commande/index.html.twig', [
            'formations' => $formationsDansPanier,
            'nomsFormationsStr' => $nomsFormationsStr, // Passer la variable à la vue si nécessaire
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
        $totalPrixCommande = 0;
        $nomsFormations = [];
    
        foreach ($formationsIdsDansPanier as $formationId => $quantity) {
            $formation = $formationsRepository->find($formationId);
            if ($formation) {
                $formationsDansPanier[] = $formation;
                $totalPrixCommande += $formation->getPrix();
                $nomsFormations[] = $formation->getTitreDeLaFormation();
            }
        }
    
        $nomsFormationsStr = implode(', ', $nomsFormations);
    
        if (empty($nomsFormationsStr) || $totalPrixCommande <= 0) {
            throw new \Exception('Les informations nécessaires pour la session de paiement sont manquantes.');
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
            $commandeDetail->setPrixtotal($totalPrixCommande);
            $commandeDetail->setStatut(0);
            $adresseUtilisateur = $this->getUser()->getAdresses()->first();
            $adresseUser = $adresseUtilisateur ? $adresseUtilisateur->getAdresseComplete() : '';
            $commandeDetail->setAdresseUser($adresseUser);
            $commandeDetails[] = $commandeDetail;
        }
    
        foreach ($commandeDetails as $commandeDetail) {
         
            $commandeDetail->setSessionStripeId('YOUR_SESSION_STRIPE_ID_HERE');
            $this->entityManager->persist($commandeDetail);
        }
        $this->entityManager->flush();
    
        Stripe::setApiKey("sk_test_51OeCoOLne9zIBO1LFsIggYXSCeeeAlmO3g1Afr1XD2Goex6leMNqAtoRklDbmHyxBun3OcdDeIQkRPGGhLKIfps500yL0fKbML");
    
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
    
        // Créez la session Stripe
        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $nomsFormationsStr,
                    ],
                    'unit_amount' => $totalPrixCommande, 
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        if (isset($checkout_session->id) && !empty($checkout_session->id)) {
            foreach ($commandeDetails as $commandeDetail) {
                $commandeDetail->setSessionStripeId($checkout_session->id);
                $this->entityManager->persist($commandeDetail);
            }
    
            $this->entityManager->flush();
    
            $cart->remove();
            return $this->redirect($checkout_session->url);
        } else {
            throw new \Exception('La création de la session Stripe a échoué.');
        }
    }

    #[Route('/continue-paiement/{id}', name: 'app_continue_paiement')]
    public function continuePaiement(int $id, CommandeDetailRepository $commandeDetailRepository): Response
    {
        $commandeDetail = $commandeDetailRepository->find($id);

        if (!$commandeDetail) {
            throw $this->createNotFoundException('Détail de commande non trouvé');
        }

        Stripe::setApiKey("sk_test_51OeCoOLne9zIBO1LFsIggYXSCeeeAlmO3g1Afr1XD2Goex6leMNqAtoRklDbmHyxBun3OcdDeIQkRPGGhLKIfps500yL0fKbML");
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        $nouvelleSessionStripe = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $commandeDetail->getFormation()->getTitreDeLaFormation(),
                    ],
                    'unit_amount' => $commandeDetail->getPrixtotal() , // Stripe requires the amount in cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);
        
        $commandeDetail->setSessionStripeId($nouvelleSessionStripe->id);
        $this->entityManager->flush();
        
        return $this->redirect($nouvelleSessionStripe->url);
        }
        
        #[Route('/success', name: 'app_success')]
        public function success(Request $request, CommandeDetailRepository $commandeDetailRepository): Response
        {
            $sessionId = $request->query->get('session_id');
        
            if (!$sessionId) {
                throw new \Exception('Session ID manquant');
            }
 
           $commandeDetail = $commandeDetailRepository->findOneBy(['sessionStripeId' => $sessionId]);
        
            if ($commandeDetail) {
                $commandeDetail->setStatut(1);
                $this->entityManager->flush();
                $message = 'Votre paiement a été réussi et votre commande est confirmée.';
            } else {
                throw new \Exception('Commande non trouvée.');
            }
        
            return $this->render('succes/index.html.twig', [
                'message' => $message ?? null,
            ]);
        }
        }
        