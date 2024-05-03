<?php

namespace App\Controller;

use App\Entity\Adresse;
use App\Form\AdresseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdresseController extends AbstractController
{
    private $entityManager;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/compte/adresse', name: 'app_adresse')]
    public function ajouter(Request $request): Response
    {
        $user = $this->getUser();
        $adresse = $user->getAdresses()->first(); 

        if (!$adresse) {
            $adresse = new Adresse();
        }

        $form = $this->createForm(AdresseType::class, $adresse);

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $adresse->setUser($user);

            $this->entityManager->persist($adresse);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_accueil_compte');
        }

        return $this->render('accueil_compte/adresse.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
