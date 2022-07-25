<?php

namespace App\Classe;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;

class Cart {
    private $ManagerRegistry;
    private $requestStack;
    

    public function __construct( RequestStack $requestStack,ManagerRegistry $ManagerRegistry )
    {
        $this->requestStack = $requestStack->getSession();
        $this->ManagerRegistry = $ManagerRegistry;
    }

    public function add($id){

        $cart = $this->requestStack->get('cart', []);
       
       if(!empty($cart[$id])){
           $cart[$id]++;
       }else{
           $cart[$id] = 1;
       }
       $this->requestStack->set('cart',$cart);
    }
    
    public function get(){
        return $this->requestStack->get('cart');
    }

    public function remove(){
        return $this->requestStack->remove('cart');
    }

    public function delete($id){

     $cart = $this->requestStack->get('cart',[]);
     unset($cart[$id]);

     return $this->requestStack->set('cart',$cart);

    }

    public function decrease($id){
      
        $cart = $this->requestStack->get('cart', []);

        if($cart[$id] >1){
            $cart[$id]--;
        }else{
            unset($cart[$id]);
        }

        return $this->requestStack->set('cart',$cart);
    }

    public function getfull(){


        $cartComplete =[];

        if($this->get()){

            foreach($this->get() as $id => $quantity){
                $product_object=$this->ManagerRegistry->getRepository(Product::class)->findOneById($id);
                if(!$product_object){
                    $this->delete($id);
                    continue;
                }
                $cartComplete[] = [
                  'product' => $product_object, 
                  'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}
