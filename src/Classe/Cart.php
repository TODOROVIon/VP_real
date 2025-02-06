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

    public function decrease($id)
    {
        $cart = $this->requestStack->getSession()->get('cart');     //notre panier en court 

        if($cart[$id]['qty'] > 1)/*on cherche dans notre variable directement sur son ID apres directement son qty*/{
            $cart[$id]['qty'] = $cart[$id]['qty'] -1;
        } else {
            unset($cart[$id]);//unset -> function PHP qui permet de supprimé une entree de mon tableau
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function fullQuantity()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $quantity = 0;

        if (!isset($cart)){
            return $quantity;
        }

        foreach ($cart as $product){
            // dd($product);
            $quantity = $quantity + $product['qty'];
        }
        // dd($quantity);
        return $quantity;
    }

    public function getTotalWt()
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $price = 0;

        if (!isset($cart)){
            return $price;
        }


        foreach ($cart as $product){
            // dd($product);
            $price = $price + ($product['object']->getPriceWt()/*getPriceWt-function du entity product*/ * $product['qty']);
        }
        // dd($quantity);
        return $price;
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