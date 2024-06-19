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

       
        $existingEmail = $this->entityManager->getRepository(EmailNewsletter::class)->findOneBy([
            'emailpournewletter' => $email,
        ]);

        if ($existingEmail) {
          
            return $this->redirectToRoute('newsletter_existante');
        }

        $emailEntity = new EmailNewsletter();
        $emailEntity->setEmailpournewletter($email);

        $this->entityManager->persist($emailEntity);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_accueil');
    }
}
