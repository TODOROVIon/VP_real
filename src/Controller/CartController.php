<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => $cart->getCart(),
        ]);
    }

    #[Route('/cart/add{id}', name: 'app_cart_add')]
    public function add($id, Cart $cart, ProductRepository $productRepository, Request $request): Response
    {
        // dd($request->headers);      // a faire attentions dans DD aux REFERER, derniere URL
        // dd($request->headers->get('referer'));

        $product = $productRepository->findOneById($id);
        
        $cart->add($product);
        // dd($id);

        $this->addFlash(
            'success',
            'Produit correctement ajouté a votre panier');

        // return $this->redirectToRoute('app_product',[
        //     'slug' => $product->getSlug(),
        // ]);          // V1

        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/cart/decrease{id}', name: 'app_cart_decrease')]
    public function decrease($id, Cart $cart): Response
    {
        // dd($request->headers);      // a faire attentions dans DD aux REFERER, derniere URL
        // dd($request->headers->get('referer'));

        $cart->decrease($id);
        // dd($id);

        $this->addFlash(
            'success',
            'Produit correctement supprimée a votre panier');

        // return $this->redirectToRoute('app_product',[
        //     'slug' => $product->getSlug(),
        // ]);          // V1

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'app_cart_remove')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();
        // dd($id);

        return $this->redirectToRoute('app_home');
    }
}
