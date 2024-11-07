<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/me-contacter', name: 'app_contact')]
    public function index(Request $request, Mail $mail): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier si le champ honeypot est rempli
            if (!empty($form->get('honeypot')->getData())) {
                // Ajouter un message d'erreur et rediriger vers la page contact
                $this->addFlash('error', 'Erreur : veuillez ne pas remplir le champ honeypot.');
                return $this->redirectToRoute('app_contact');
            }

            // Traitement normal si le honeypot est vide
            $data = $form->getData();
            $to_name = $data['Prenom'] . ' ' . $data['Nom'];
            $content = "Nom: " . $data['Nom'] . "\n" .
                       "Prénom: " . $data['Prenom'] . "\n" .
                       "Email: " . $data['Email'] . "\n" .
                       "Sujet: " . $data['Sujet'] . "\n" .
                       "Message: " . $data['VotreMessage'];

            $mail->send(
                'flavie.c@sensations-coaching.com',
                $to_name,
                'Nouveau contact: ' . $data['Sujet'],
                $content
            );

            $this->addFlash('notice', 'Merci de me contacter, je vais vous répondre au plus vite.');
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
