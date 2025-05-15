<?php

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class OrderController extends AbstractController
{
    /*
        1ere etape du tunnel d'achat
            Choix de l'addresse de livraison et du transporteur
    */

    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {
        $addresses = $this->getUser()->getAddresses();
        if (count($addresses)==0){
            return $this->redirectToRoute('app_account_address_form');
        }
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary'),
        ]);

        return $this->render('order/index.html.twig', [
            'deliveryForm' => $form->createView(),
        ]);
    }

    /*
        2eme etape du tunnel d'achat
            Recapitulatif de la commande de l'utilisateur
            Insertion dans la BDD
            Preparation du payement vers Stripe
    */
    // methods: ['POST']
    #[Route('/commande/recapitulatif', name: 'app_order_summary' )]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        if ($request->getMethod() != 'POST'){
            return $this->redirectToRoute('app_cart');
        }

        $products =$cart->getCart();
        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Creation de la chaine adresse
            // dd($form->get('addresses')->getData());
            $addresseObj = $form->get('addresses')->getData();

            $addresse = $addresseObj->getFirstname().' '.$addresseObj->getLastname().'<br/>';
            $addresse .= $addresseObj->getAddress().'<br/>';
            $addresse .= $addresseObj->getPostal().' '.$addresseObj->getCity().'<br/>';
            $addresse .= $addresseObj->getCountry().'<br/>';
            $addresse .= $addresseObj->getPhone().'<br/>';
            
            // dd($cart);
            // Stocker les information dans BDD
            $order = new Order();
            $order->setCreatedAt(new DateTime());
            $order->setState(3);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($addresse);
            $order->setUser($this->getUser());
            
            foreach ($products as $product){
                // dd($products);
                $orderDetail = new OrderDetail();
                $orderDetail->setProductName($product['object']->getName());
                $orderDetail->setProductIllustration($product['object']->getIllustration());
                $orderDetail->setProductPrice($product['object']->getPrice());
                $orderDetail->setProductTva($product['object']->getTva());
                $orderDetail->setProductQuantity($product['qty']);
                $order->addOrderDetail($orderDetail);
            }

            $entityManager->persist($order);
            $entityManager->flush();

        }

        return $this->render('order/summary.html.twig',[
            'choises' => $form->getData(),
            'cart' => $products,
            'totalWt' => $cart->getTotalWt(),
        ]);
    }

}
