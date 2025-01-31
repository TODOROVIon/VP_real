<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {

    }

    public function add($product)
    {
        // dd($product);
        //Appeler la session de symfony
        $cart = $this->requestStack->getSession()->get('cart'); //permetre le pannier en cours
        // dd($session);
        
        //Ajouter une quantite +1 a mon produit
        if(isset($cart[$product->getId()])){
        $cart[$product->getId()] = [
            'object' => $product,
            'qty' =>  $cart[$product->getId()]['qty'] + 1
            ];
        } else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' =>  1
            ];
        }

        //Creer ma session Cart
        $this->requestStack->getSession()->set('cart', $cart);
        // dd($this->requestStack->getSession()->get('cart'));
    }

    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
}