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
            $utilisateur = $form->getData();

            $search_email = $entityManager->getRepository(Utilisateur::class)->findOneBy(['email' => $utilisateur->getEmail()]);

            if (!$search_email) {
                $password = $encoder->hashPassword($utilisateur, $utilisateur->getPassword());
                $utilisateur->setPassword($password);

                $entityManager->persist($utilisateur);
                $entityManager->flush();

                $mail = new Mail();
                $content= "Bonjour ".$utilisateur->getPrenom()."</br> Bienvenue chez Sensation Coaching.<br><br/>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam massa orci, sodales eu vestibulum at, gravida eget lacus. Sed maximus purus in auctor fermentum. Quisque elementum vitae magna eu egestas. Aliquam magna ante, aliquam ac iaculis ac, fringilla a sem. Etiam vulputate diam vitae turpis malesuada, vitae blandit nibh dictum. Pellentesque condimentum, magna sit amet imperdiet tincidunt, purus purus feugiat purus, in accumsan nunc nisl ut leo. Integer blandit, mauris in ultrices accumsan, enim tortor hendrerit nisl, vitae imperdiet lorem mauris at erat. Duis at feugiat felis, pharetra consectetur elit. Morbi porta urna lectus, sit amet consectetur nisl lacinia sit amet. Proin sed purus id diam tincidunt sodales. Donec tempor faucibus odio, at placerat mauris cursus vitae. Cras eget vehicula ante, ut vulputate sapien. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Proin eget sodales risus, sed fringilla sapien.";
                $mail->send($utilisateur->getEmail(), $utilisateur->getPrenom(), 'Bienvenue chez Sensation Coaching',$content );
                
                $notification = "Votre inscription s'est correctement déroulée. Vous pouvez vous connecter à votre compte.";

            } else {
                $notification = "L'email que vous avez renseigné existe déjà.";
            }

        }

        return $this->render('inscription/index.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
