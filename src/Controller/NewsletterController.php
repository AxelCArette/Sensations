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
        $email = $request->request->get('emailpournewletter');

        // Vérifier si l'email existe déjà en base (optionnel)
        $existingEmail = $this->entityManager->getRepository(EmailNewsletter::class)->findOneBy([
            'emailpournewletter' => $email,
        ]);

        if ($existingEmail) {
            // Gérer le cas où l'email existe déjà
            // Vous pouvez rediriger vers une autre page par exemple
            return $this->redirectToRoute('newsletter_existante');
        }

        // Enregistrer l'email en base de données
        $emailEntity = new EmailNewsletter();
        $emailEntity->setEmailpournewletter($email);

        $this->entityManager->persist($emailEntity);
        $this->entityManager->flush();

        // Rediriger vers une page de confirmation
        return $this->redirectToRoute('app_accueil');
    }
}
