<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(private RequestStack $requestStack)
    {

    }

    //     
    // add()
    // Function permettant l'ajout d'un produit dans le panier    
    // 
    public function add($product)
    {
        // dd($product);
        //Appeler la session de symfony
        $cart = $this->getCart(); //permetre le pannier en cours
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

    //     
    // decrease()
    // Function permettant la suppression d'une quantité d'un produit dans le panier    
    // 
    public function decrease($id)
    {
        $cart = $this->getCart();     //notre panier en court 

        if($cart[$id]['qty'] > 1)/*on cherche dans notre variable directement sur son ID apres directement son qty*/{
            $cart[$id]['qty'] = $cart[$id]['qty'] -1;
        } else {
            unset($cart[$id]);//unset -> function PHP qui permet de supprimé une entree de mon tableau
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    //     
    // fullQuantity()
    // Function returnant le nombre total de produit au panier    
    // 
    public function fullQuantity()
    {
        $cart = $this->getCart();
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

    //     
    // getTotalWt()
    // Function returnant le prix total des produits au panier    
    // 
    public function getTotalWt()
    {
        $cart = $this->getCart();
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
    
    //     
    // getCart()
    // Function returnant le panier    
    // 
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }

    //     
    // remove()
    // Function permettant de supprimer totalement le contenu de panier    
    // 
    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }
}