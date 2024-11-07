<?php

namespace App\Controller;

use App\Entity\EmailNewsletter;
use App\Form\EmailNewsletterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_accueil')]
    public function index(Request $request): Response
    {
        $emailNewsletter = new EmailNewsletter();
        $form = $this->createForm(EmailNewsletterType::class, $emailNewsletter);
        $form->handleRequest($request);
    
        // Vérifie si le honeypot est rempli
        // Utilisez $form->get('honeypot')->getData() pour obtenir la valeur
        $honeypot = $form->get('honeypot')->getData();
        if (!empty($honeypot)) {
            $this->addFlash('error', 'Le formulaire a été soumis de manière suspecte.');
            return $this->redirectToRoute('app_accueil');
        }
    
        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si l'adresse e-mail est déjà dans la base de données
            $existingEmail = $this->entityManager->getRepository(EmailNewsletter::class)->findOneBy([
                'emailpournewletter' => $emailNewsletter->getEmailpournewletter()
            ]);
    
            if ($existingEmail) {
                $this->addFlash('warning', 'Cette adresse e-mail est déjà inscrite à la newsletter.');
                return $this->redirectToRoute('app_accueil');
            }
    
            // Sauvegarder l'adresse e-mail dans la base de données
            $this->entityManager->persist($emailNewsletter);
            $this->entityManager->flush();
    
            $this->addFlash('success', 'Inscription à la newsletter réussie !');
            return $this->redirectToRoute('app_accueil');
        }
    
        return $this->render('accueil/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    
}
