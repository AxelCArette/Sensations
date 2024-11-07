<?php

namespace App\Controller;

use App\Entity\EmailNewsletter;
use App\Form\EmailNewsletterType;
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
        $emailNewsletter = new EmailNewsletter();
        $form = $this->createForm(EmailNewsletterType::class, $emailNewsletter);
        $form->handleRequest($request);
    
        // Vérifie si le honeypot est rempli
        $honeypot = $request->request->get('honeypot');
        if (!empty($honeypot)) {
            $this->addFlash('error', 'Le formulaire a été soumis de manière suspecte.');
            return $this->redirectToRoute('app_accueil');
        }

        // Vérifie si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer l'adresse e-mail depuis le formulaire
            $email = $emailNewsletter->getEmailpournewletter();

            // Validation de l'adresse e-mail
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

            // Sauvegarder l'adresse e-mail dans la base de données
            $this->entityManager->persist($emailNewsletter);
            $this->entityManager->flush();
    
            // Message flash de succès
            $this->addFlash('success', 'Vous avez été inscrit avec succès !');
    
            return $this->redirectToRoute('app_accueil');
        }

        // Si le formulaire n'est pas soumis ou pas valide, retourner à la vue
        return $this->render('newsletter/subscribe.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
