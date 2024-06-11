<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\Utilisateur;
use App\Form\ResetPasswordType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mot-de-passe-oublie', name: 'app_reset_password')]
    public function index(Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_accueil');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $request->get('email')]);

            if ($user) {
                $resetPassword = new ResetPassword();
                $resetPassword->setUtilisateur($user);
                $resetPassword->setToken(uniqid());
                $resetPassword->setCreatedAt(new DateTime());
                $this->entityManager->persist($resetPassword);
                $this->entityManager->flush();

                $url = $this->generateUrl('app_update_password', [
                    'token' => $resetPassword->getToken()
                ], UrlGeneratorInterface::ABSOLUTE_URL);

                $content = "Bonjour " . $user->getPrenom() . "<br> Si vous n'avez pas demandé à réinitialiser votre mot de passe, ne prenez pas en compte cet email.<br>";
                $content .= "Si vous avez demandé la réinitialisation de votre mot de passe sur Sensations,<br><br>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a href='" . $url . "'>mettre à jour votre mot de passe</a>.";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getPrenom(), 'Réinitialiser votre mot de passe', $content);
                $this->addFlash('notice', 'Vous allez recevoir un email pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/modifier-mon-mot-de-passe/{token}', name: 'app_update_password')]
    public function update(string $token, Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $resetPassword = $this->entityManager->getRepository(ResetPassword::class)->findOneBy(['Token' => $token]);

        if (!$resetPassword) {
            return $this->redirectToRoute('app_reset_password');
        }
        
        $now = new DateTime();
        if ($now > $resetPassword->getCreatedAt()->modify('+ 1 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveler.');
            return $this->redirectToRoute('app_reset_password');
        }
        
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd=$form->get('new_password')->getData();
                $password = $encoder->hashPassword($resetPassword->getUtilisateur(), $new_pwd);
                $resetPassword->getUtilisateur()->setPassword($password);
                $this->entityManager->flush(); 
            $this->addFlash('notice','Votre mot de passe à bien était modifier');
            return $this->redirectToRoute('app_login');
        }
        
        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
