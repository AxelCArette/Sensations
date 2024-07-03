<?php

namespace App\Controller;

use App\Entity\EmailNewsletter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/newsletter', name: 'app_newsletter', methods: ['POST'])]
    public function subscribeToNewsletter(Request $request): Response
    {
        // Récupérer l'adresse e-mail depuis la requête
        $email = $request->request->get('emailpournewletter');

        // Valider l'adresse e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->addFlash('error', 'L\'adresse e-mail fournie est invalide.');
            return $this->redirectToRoute('app_newsletter');
        }

        // Vérifier si l'adresse e-mail est déjà dans la base de données
        $existingEmail = $this->entityManager->getRepository(EmailNewsletter::class)->findOneBy([
            'emailpournewletter' => $email,
        ]);

        if ($existingEmail) {
            $this->addFlash('error', 'Cette adresse e-mail est déjà inscrite.');
            return $this->redirectToRoute('app_accueil');
        }

        // Si l'adresse e-mail est unique, la sauvegarder dans la base de données
        $emailNewsletter = new EmailNewsletter();
        $emailNewsletter->setEmailpournewletter($email);

        $this->entityManager->persist($emailNewsletter);
        $this->entityManager->flush();

        // Ajouter un message flash de succès
        $this->addFlash('success', 'Vous avez été inscrit avec succès !');

        // Rediriger vers la page d'accueil ou une autre page
        return $this->redirectToRoute('app_accueil');
    }
}
