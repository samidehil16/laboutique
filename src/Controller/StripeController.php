<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    
    #[Route('/order/create-session/{reference}', name: 'stripe_create_session' )]
    public function index(EntityManagerInterface $entityManager,Cart $cart, $reference): Response
    {
        $product_for_stripe= [];
        $YOUR_DOMAIN ='http://127.0.0.1:8000';
        
        $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);

        if(!$order){
           new JsonResponse(['error'=> 'order']);
        }

        foreach ($order->getOrderDetails()->getValues() as $product) {
            $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct());
            $product_for_stripe[] = [
                'price_data'=>[
                    'currency'=>'eur',
                    'unit_amount'=>$product->getPrice(),
                    'product_data'=>[
                        'name'=>$product->getProduct(),
                        'images'=>[$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity'=>$product->getQuantity(),
              ];
        }


        $product_for_stripe[] = [
            'price_data'=>[
                'currency'=>'eur',
                'unit_amount'=>$order->getCarrierPrice(),
                'product_data'=>[
                    'name'=>$order->getCarrierName(),
                    'images'=>[$YOUR_DOMAIN],
                ],
            ],
            'quantity'=>1,
          ];

        Stripe::setApiKey('sk_test_51KjJvlGFYAI99P55YpB7OSEk2PYodO7PahsXxKMj55VLPPYbGoaA93gSnxw0ptBnsmQHfPgIz47Do0dzRP9jPXQL00TkdcgdYH');

        

        $checkout_session = CheckoutSession::create([
            'customer_email'=> $this->getUser()->getEmail(),
            'payment_method_types'=>['card'],
            'line_items'=>[
                $product_for_stripe
            ],
            'mode'=>'payment',
            'success_url'=>$YOUR_DOMAIN.'/order/merci/{CHECKOUT_SESSION_ID}',
            'cancel_url'=>$YOUR_DOMAIN.'/order/erreur/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setstripeSessionId($checkout_session->id);
        $entityManager->flush();

        $response = new JsonResponse(['id'=> $checkout_session->id]);
        return $response;
    }

}
