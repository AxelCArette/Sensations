<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\CommandeDetail;
use App\Classe\Cart;
use App\Classe\Mail;
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
        $nomsFormations = [];

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
            'nomsFormationsStr' => $nomsFormationsStr,
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
        //sk_test_51OeCoOLne9zIBO1LFsIggYXSCeeeAlmO3g1Afr1XD2Goex6leMNqAtoRklDbmHyxBun3OcdDeIQkRPGGhLKIfps500yL0fKbML

        Stripe::setApiKey("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 ");
        $YOUR_DOMAIN = 'https://sensations-coaching.com/';

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card', 'paypal'],
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
            'allow_promotion_codes' => true,
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/cancel?session_id={CHECKOUT_SESSION_ID}',
        ]);

        if (isset($checkout_session->id) && !empty($checkout_session->id)) {
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

                $commandeDetail->setSessionStripeId($checkout_session->id);

                $commandeDetails[] = $commandeDetail;
            }

            foreach ($commandeDetails as $commandeDetail) {
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

        Stripe::setApiKey("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");
        $YOUR_DOMAIN = 'https://sensations-coaching.com/';
        //http://localhost:8000


        $nouvelleSessionStripe = Session::create([
            'payment_method_types' => ['card', 'paypal'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => $commandeDetail->getFormation()->getTitreDeLaFormation(),
                    ],
                    'unit_amount' => $commandeDetail->getPrix(),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $YOUR_DOMAIN . '/cancel?session_id={CHECKOUT_SESSION_ID}',
        ]);

        $commandeDetail->setSessionStripeId($nouvelleSessionStripe->id);
        $commandeDetail->setPrixtotal($commandeDetail->getprix());
        $this->entityManager->flush();

        return $this->redirect($nouvelleSessionStripe->url);
    }


    #[Route('/success', name: 'app_success')]
    public function success(Request $request, CommandeDetailRepository $commandeDetailRepository, EntityManagerInterface $entityManager, Mail $mail): Response
    {
        $sessionId = $request->query->get('session_id');

        if (!$sessionId) {
            throw new \Exception('Session ID manquant');
        }

        Stripe::setApiKey("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");
        $session = Session::retrieve($sessionId);

        if ($session && $session->payment_status === 'paid') {
            $totalPaid = $session->amount_total;

            $commandeDetails = $commandeDetailRepository->findBy(['sessionStripeId' => $sessionId]);

            if (count($commandeDetails) === 0) {
                throw new \Exception('Commande non trouvée.');
            }
            $message = 'Votre paiement a été réussi et votre commande est confirmée. Vous pouvez la retrouver dans votre compte client.';

            foreach ($commandeDetails as $commandeDetail) {
                $commandeDetail->setStatut(1);
                $commandeDetail->setPrixtotal($totalPaid);
                $commande = $commandeDetail->getCommande();
                $utilisateur = $commande->getUtilisateur();
                $content = "Bonjour " . $utilisateur->getPrenom() . "";
                $mail->sendTemplateB($utilisateur->getEmail(), $utilisateur->getPrenom(), '', $content);
            }

            $entityManager->flush();
        } else {
            throw new \Exception('La session de paiement n\'a pas été trouvée ou n\'a pas été payée.');
        }

        return $this->render('succes/index.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/cancel', name: 'app_cancel')]
    public function cancel(Request $request, CommandeDetailRepository $commandeDetailRepository): Response
    {
        $sessionId = $request->query->get('session_id');

        $commandeDetail = $commandeDetailRepository->findOneBy(['sessionStripeId' => $sessionId]);

        return $this->render('cancel/index.html.twig', [
            'message' => 'Votre commande a été annulée avec succès.'
        ]);
    }
}
