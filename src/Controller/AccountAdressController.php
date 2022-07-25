<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Address;
use App\Form\AdressType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAdressController extends AbstractController
{
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/adress', name: 'account_adress')]
    public function index(): Response
    {
        return $this->render('account/adress.html.twig', [
            
        ]);
    }

    #[Route('/account/add-adress', name: 'account_adress_add')]
    public function add( Cart $cart,Request $request): Response
    {   
        $address = new Address();
        $form = $this->createForm(AdressType::class , $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $address->setUser($this->getUser());
            $this->entityManager->persist($address);
            $this->entityManager->flush();
            if( $cart->get()){
                return $this->redirectToRoute('order');
            }else{

                return $this->redirectToRoute('account_adress');
            }
        }

        return $this->render('account/form_adress.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/account/uppdate-adress/{id}', name: 'account_adress_update')]
    public function update( Request $request,$id): Response
    {   
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if(!$address || $address->getUser() != $this->getUser() ){
            return $this->redirectToRoute('account_adress');
        }

        $form = $this->createForm(AdressType::class , $address);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();
            return $this->redirectToRoute('account_adress');
        }

        return $this->render('account/form_adress.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    #[Route('/account/delete-adress/{id}', name: 'account_adress_delete')]
    public function delete($id): Response
    {
        $address = $this->entityManager->getRepository(Address::class)->findOneById($id);

        if ($address && $address->getUser() == $this->getUser()) {
            $this->entityManager->remove($address);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('account_adress');
    }
}


