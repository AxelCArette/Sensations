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
    private $entityManager;

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

        if ($form->isSubmitted() && $form->isValid()) {
            $existingEmail = $this->entityManager->getRepository(EmailNewsletter::class)->findOneBy([
                'emailpournewletter' => $emailNewsletter->getEmailpournewletter()
            ]);

            if ($existingEmail) {
                $this->addFlash('warning', 'Cette adresse email est déjà inscrite à la newsletter.');
                return $this->redirectToRoute('app_accueil');
            }

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
