<?php

namespace App\Controller;

use App\Form\ChangerSonMotDePasseType;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ModifmotdepasseController extends AbstractController
{
    private $entityManager; 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/modifier-mon-mot-de-passe', name: 'app_modifmotdepasse')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ChangerSonMotDePasseType::class, $user);

        $form->handleRequest($request);

        $notification = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();

            if ($encoder->isPasswordValid($user, $old_pwd)) {
                
                $new_pwd = $form->get('new_password')->getData();
                $password = $encoder->hashPassword($user, $new_pwd);

                $user->setPassword($password);

                $this->entityManager->flush(); 
                $notification = "Votre mot de passe a bien été mis à jour";
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }

        return $this->render('accueil_compte/modifmotdepasse.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
