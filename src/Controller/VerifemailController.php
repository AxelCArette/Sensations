<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerifemailController extends AbstractController
{
    #[Route('/verifemail', name: 'app_verifemail')]
    public function index(): Response
    {
        return $this->render('verifemail/index.html.twig', [
            'controller_name' => 'VerifemailController',
        ]);
    }

    #[Route('/verify-email/{token}', name: 'app_verify_email')]
    public function verifyEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        // Rechercher l'utilisateur par le token
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->findOneBy(['token' => $token]);

        if (!$utilisateur || $utilisateur->getStatutverifier() === 'vérifié') {
            return $this->render('verifemail/error.html.twig', [
                'message' => 'Ce lien de vérification est invalide ou votre compte est déjà vérifié.',
            ]);
        }

        // Mettre à jour le statut de vérification
        $utilisateur->setStatutverifier('vérifié');

        // Sauvegarder les modifications en base de données
        $entityManager->flush();

        return $this->render('verifemail/success.html.twig', [
            'message' => 'Votre compte a été vérifié avec succès.',
        ]);
    }
}
