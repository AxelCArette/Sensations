<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Commande;
use App\Repository\FormationsRepository; // Utilisation de FormationsRepository
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    private $entityManager;
    private $formationsRepository;

    public function __construct(EntityManagerInterface $entityManager, FormationsRepository $formationsRepository)
    {
        $this->entityManager = $entityManager;
        $this->formationsRepository = $formationsRepository;
    }

    #[Route('/compte/mon-panier', name: 'app_cart')]
    public function index(Cart $cart, Request $request): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

    
        $commande = new Commande();
        $commande->setUtilisateur($this->getUser());
        $commande->setDateDeCreationCommande(new \DateTime());
    
        $reference = date('dmYHis') . '-' . uniqid();
        $commande->setReference($reference);
    
        $this->entityManager->persist($commande);
        $this->entityManager->flush();


        $cartComplete = [];

        foreach ($cart->get() as $id => $quantity) {
            $formation = $this->formationsRepository->find($id); 

            if ($formation) {
                $cartComplete[] = [
                    'formations' => $formation,
                    'quantity' => $quantity
                ];
            }
        }
        return $this->render('cart/index.html.twig', [
            'cart' => $cartComplete
        ]);
    }

    #[Route('compte/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id): Response
    {

        $cart->add($id, []);

        return $this->redirectToRoute('app_cart');
    }
    
    #[Route('compte/cart/delete/{id}', name: 'delete_formation')]
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);

        return $this->redirectToRoute('app_cart');
    }
    
    #[Route('compte/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('app_cart');
    }
}
