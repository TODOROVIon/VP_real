<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function add(Request $request, Cart $cart): Response
    {
        if ($request->getMethod() != 'POST'){
            return $this->redirectToRoute('app_cart');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // dd($form->getData());
            // Stocker les information dans BDD
        }

        return $this->render('order/summary.html.twig',[
            'choises' => $form->getData(),
            'cart' => $cart->getCart(),
            'totalWt' => $cart->getTotalWt(),
        ]);
    }

}
