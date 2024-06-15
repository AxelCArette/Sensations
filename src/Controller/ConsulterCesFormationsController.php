<?php

namespace App\Controller;

use App\Entity\CommandeDetail;
use App\Repository\CommandeDetailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ConsulterCesFormationsController extends AbstractController
{
    #[Route('compte/consulter-ces-formations', name: 'app_consulter_ces_formations')]
    public function index(CommandeDetailRepository $commandeDetailRepository): Response
    {
        $commandeDetails = $commandeDetailRepository->findAll();
        $commandes = [];
        foreach ($commandeDetails as $commandeDetail) {
            $commande = $commandeDetail->getCommande();
            if (!in_array($commande, $commandes)) {
                $commandes[] = $commande;
            }
        }

        return $this->render('accueil_compte/consultercesformations.html.twig', [
            'commandes' => $commandes, // Passer la liste des commandes à la vue
            'commandeDetails' => $commandeDetails
        ]);
    }

    #[Route('compte/download-pdf/{id}', name: 'download_pdf')]
    public function downloadPdf(CommandeDetail $commandeDetail = null): Response
    {
        if ($commandeDetail === null) {
            $errorMessage = 'Cette formation n\'existe pas.';
            return $this->render('error403.html.twig', [
                'errorMessage' => $errorMessage
            ]);
        }

        $user = $this->getUser();

        if ($commandeDetail->getStatut() !== 1 || $commandeDetail->getCommande()->getUtilisateur() !== $user) {
            $errorMessage = 'Vous n\'êtes pas autorisé à télécharger ce PDF.';
            return $this->render('error403.html.twig', [
                'errorMessage' => $errorMessage
            ]);
        }

        $pdfPath = $this->getParameter('kernel.project_dir') . '/public/uploads/pdf/' . $commandeDetail->getFormation()->getFichierPDF();

        if (!file_exists($pdfPath)) {
            throw $this->createNotFoundException('Le fichier PDF demandé n\'existe pas.');
        }

        $response = new BinaryFileResponse($pdfPath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
    }

    #[Route('/compte/delete-formation/{id}', name: 'delete_formation')]
    public function deleteFormation(CommandeDetail $commandeDetail, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si l'utilisateur a le droit de supprimer cette formation
        if ($commandeDetail->getStatut() !== 0 || $commandeDetail->getCommande()->getUtilisateur() !== $this->getUser()) {
            throw new AccessDeniedException('Vous n\'avez pas le droit de supprimer cette formation.');
        }

        // Supprimer la commande de la base de données
        $entityManager->remove($commandeDetail);
        $entityManager->flush();

        // Rediriger vers la page de consultation des formations
        return $this->redirectToRoute('app_consulter_ces_formations');
    }

    #[Route('/compte/delete-all-formations-in-commande', name: 'delete_all_formations_in_commande', methods: ['POST'])]
    public function deleteAllFormationsInCommande(Request $request, EntityManagerInterface $entityManager): Response
    {
    
        if (!$this->getUser()) {
            throw new AccessDeniedException('Vous devez être connecté pour effectuer cette action.');
        }

        $commandeId = $request->request->get('commandeId');

        
        $commandeDetails = $entityManager->getRepository(CommandeDetail::class)->findBy(['commande' => $commandeId]);

 
        if (empty($commandeDetails)) {
            throw $this->createNotFoundException('Aucune formation n\'est associée à cette commande.');
        }

       
        foreach ($commandeDetails as $commandeDetail) {
            if ($commandeDetail->getStatut() !== 0 || $commandeDetail->getCommande()->getUtilisateur() !== $this->getUser()) {
                throw new AccessDeniedException('Vous n\'avez pas le droit de supprimer toutes les formations dans cette commande.');
            }
        }

        foreach ($commandeDetails as $commandeDetail) {
            $entityManager->remove($commandeDetail);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_consulter_ces_formations');
    }
}
