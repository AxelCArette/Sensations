<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder, UrlGeneratorInterface $urlGenerator): Response
    {
        $notification = null;

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Protection anti-spam
            $honeypot = $form->get('honeypot')->getData();

            if (!empty($honeypot)) {
                $notification = 'Votre inscription a été détectée comme du spam.';
            } else {
                // Récupérer les données soumises par le formulaire
                $utilisateur = $form->getData();

                // Vérifier si l'email existe déjà dans la base de données
                $search_email = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $utilisateur->getEmail()]);

                if (!$search_email) {
                    // Hacher le mot de passe avant de l'enregistrer
                    $password = $encoder->hashPassword($utilisateur, $utilisateur->getPassword());
                    $utilisateur->setPassword($password);

                    // Générer un token unique pour l'utilisateur
                    $token = bin2hex(random_bytes(32));  // Générer un token de 64 caractères hexadécimaux
                    $utilisateur->setToken($token);  // Définir le token pour l'utilisateur

                    // Définir le statut de vérification comme "non vérifié"
                    $utilisateur->setStatutverifier('non vérifié');

                    // Enregistrer l'utilisateur dans la base de données
                    $entityManager->persist($utilisateur);
                    $entityManager->flush();

                    // Générer le lien de vérification avec le token
                    $verificationUrl = $urlGenerator->generate('app_verify_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                    $mail = new Mail();
                    $content = "Bonjour " . $utilisateur->getPrenom() . ", veuillez cliquer sur le lien suivant pour vérifier votre compte : <a href=\"" . $verificationUrl . "\">Vérifier mon compte</a>";
                    $mail->sendTemplateA($utilisateur->getEmail(), $utilisateur->getPrenom(), '', $content);

                    // Notification de succès
                    $notification = "Votre inscription s'est correctement déroulée. Un email de confirmation a été envoyé. Veuillez vérifier votre boîte de réception.";
                } else {
                    // Notification si l'email existe déjà
                    $notification = "L'email que vous avez renseigné existe déjà.";
                }
            }
        }

        return $this->render('inscription/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
