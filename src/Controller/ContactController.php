<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class ContactController extends AbstractController
{
    #[Route('/me-contacter', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form=$this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->addFlash('notice','Merci de me contacter, je vais vous répondre au plus vite.');
            // $mail = new Mail();
            // $mail->send('','Sensation');
        }

        return $this->render('contact/index.html.twig',[
            'form'=>$form->createView()

        ]);
    }
}
