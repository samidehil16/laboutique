<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{

    private $ManagerRegistry;

    public function __construct(ManagerRegistry $ManagerRegistry)
    {
        $this->ManagerRegistry = $ManagerRegistry;
    }

    #[Route('/my-cart', name: 'cart')]
    public function index(Cart $cart ): Response
    {   
      
        return $this->render('cart/index.html.twig', [
            'cart'=> $cart->getfull()
        ]);
    }


    #[Route('/my-cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart,$id): Response
    {
        ($cart->add($id));
        return $this->redirectToRoute('cart');
    }

    #[Route('/my-cart/remove', name: 'remove-my-cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        return $this->redirectToRoute('products');
    }

    #[Route('/my-cart/delete/{id}', name: 'delete-to-cart')]
    public function delete(Cart $cart,$id): Response
    {
        $cart->delete($id);
        return $this->redirectToRoute('cart');
    }

    #[Route('/my-cart/decrease/{id}', name: 'decrease-to-cart')]
    public function decrease(Cart $cart,$id): Response
    {
        $cart->decrease($id);
        return $this->redirectToRoute('cart');
    }
}
