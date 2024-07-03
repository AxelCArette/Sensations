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
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;

        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $honeypot = $form->get('honeypot')->getData();

            if (!empty($honeypot)) {

            
                $notification = 'Votre inscription a été détectée comme du spam.';

            } else {

                $utilisateur = $form->getData();

                $search_email = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $utilisateur->getEmail()]);

                if (!$search_email) {
                    $password = $encoder->hashPassword($utilisateur, $utilisateur->getPassword());
                    $utilisateur->setPassword($password);

                    $entityManager->persist($utilisateur);
                    $entityManager->flush();

                    $mail = new Mail();
                    $content= "Bonjour ".$utilisateur->getPrenom()."";
                    $mail->sendTemplateA($utilisateur->getEmail(), $utilisateur->getPrenom(), '', $content);
                    $loginUrl = $this->generateUrl('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL);

                 
                    $notification = "Votre inscription s'est correctement déroulée. Vous pouvez vous connecter à votre compte par ici : <a href=\"" . $loginUrl . "\">Se connecter</a>";
                   

                } else {
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
